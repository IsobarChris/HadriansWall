/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * HadriansWall implementation : © Chris Steele <steele22374@gmail.com>
 *
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 *
 * hadrianswall.js
 *
 * HadriansWall user interface script
 * 
 * In this file, you are describing the logic of your user interface, in Javascript language.
 *
 */

function log(prefix,msg) {
    console.log(`--] ${prefix}:`,msg);
}

function debug(prefix,msg) {
    console.log(`--] DEBUG (${prefix}): `,msg);
}

function error(msg) {
    console.log(`--] ERROR: `,msg);
}

define([
    "dojo","dojo/_base/declare",
    "ebg/core/gamegui",
    "ebg/counter"
],
function (dojo, declare) {
    return declare("bgagame.hadrianswall", ebg.core.gamegui, {
        constructor: function(){
            log('constructor','hadrianswall');              
        },
        
        /*
            setup:
        */
        
        setup: function( gamedatas )
        {
            log('setup',"Starting game setup" );
            debug('gamedatas',gamedatas);

            // add div for each location on the player sheets
            this.addScratchLocations();
            dojo.query('.donext').removeClass('clickable');

            dojo.query('.clickable').connect('onclick',this,'onBoxClicked');

            log('info',`Round ${gamedatas.round}`)
            log('info',`Difficulty ${gamedatas.difficulty}`)
            log('info',`Attack Potential ${gamedatas.attack_potential}`)
            log('info',`Attacks ${gamedatas.attacks}`)

            
            // pull data from gamedatas and clean it up
            let scores={};
            let scores_map = gamedatas.scores;
            for(s in scores_map) {
                let id = scores_map[s].id;
                scores[id] = scores_map[s];
            }
            gamedatas.scores = scores;

            // let paths={};
            // let paths_map = gamedatas.paths;
            // let path_objs = Object.values(paths_map[0]);
            // path_objs.forEach((g)=>{
            //     paths[g.id] = g;
            // })
            // gamedatas.paths = paths;

            // Setting up player panels
            for( var player_id in gamedatas.players )
            {
                var player = gamedatas.players[player_id];
                this.setupPlayer(player_id,player,gamedatas);                
            }

            this.setupCurrentPlayer(gamedatas);
 
            // Setup game notifications to handle (see "setupNotifications" method below)
            this.setupNotifications();

            log("setup","Ending game setup" );
        },

        setupCurrentPlayer: function(gamedatas) {            
            let resources = gamedatas.resources;
            debug("resources",resources);
            // add resource counters
            [`civilians`,`servants`,`soldiers`,`builders`,`bricks`].forEach(resource=>{
                let counter = new ebg.counter();
                let dom_id = `${resource}_resource`
                counter.create(dom_id);
                counter.setValue(parseInt(resources[resource]));
                this[`${resource}_resource`] = counter;
            });

            let score_column = ['renown','piety','valour','discipline','path','disdain','total'];
            score_column.forEach((row)=>{
                let dom_id = `score_${row}`;
                this[dom_id] = new ebg.counter();
                this[dom_id].create(dom_id);
            })

            this.updateResources(gamedatas.resources);
            this.updatePaths(gamedatas.paths);
            this.updateScoreColumn(gamedatas.score_column);

        },

        setupPlayer: function (player_id, player_info, gamedatas) {
            // move miniboards to the right
            var color = this.gamedatas.players[player_id].color;
            var player = this.gamedatas.players[player_id];
            var content = dojo.byId('player_panel_content_' + color);
            var status = 'player_table_status_' + player_id;
             var score = document.querySelector("#player_board_" + player_id + " .player_score");
            if (score) dojo.place(status, content, 'after');
            var x = content.querySelector('#miniboard_' + color);
            if (x) {
                dojo.destroy(x);
            }
            dojo.place('miniboard_' + color, content);
            
            debug("Setup Player",gamedatas.board);
            if(gamedatas.board){
                let board = gamedatas.board;
                this.drawAllScratches(board);
            }

            ['renown','piety','valour','discipline','disdain'].forEach( attr=>{
                try { // because the current player will not have these set
                    // TODO: place these on sheet 1 in the scoring section for current player
                    let counter = new ebg.counter();
                    let dom_id = `${attr}_score_${player.color}`
                    counter.create(dom_id);
                    counter.setValue(parseInt(gamedatas.scores[player_id][attr]));

                    this[`${attr}_score_${player_id}`] = counter;
                }catch(e){
                    error("counter error: (expected) ",e)
                }
            });                

            // TODO: get this from player_paths 
            // [1,2,3,4,5,6].forEach((i)=>{
            //     if(gamedatas.paths[player_id][`round_${i}`]>0) {
            //         let card_num = gamedatas.paths[player_id][`round_${i}`];
            //         let node = dojo.query(`#path${i}_${player.color}`);
            //         node.addClass(`player_card_${card_num}`);  
            //     }
            // })
        },
   

        ///////////////////////////////////////////////////
        //// Game & client states
        onEnteringState: function( stateName, args )
        {
            debug('Entering state',stateName);
            
            switch( stateName )
            {
                case 'acceptFateResources': {
                    let fate = args.args;
                    debug("card",fate);

                    dojo.removeClass('fate','forcehidden');
                    dojo.removeClass('fate_card');

                    dojo.addClass('fate_card',['fatecardsheet',fate.card,'card_in_hand']);
                }
                break;

                case 'acceptProducedResources': {
                    let brick_node = '<div class="iconsheet icon_brick"></div>';
                    let civilian_node = '<div class="iconsheet icon_civilian"></div>';
                    let builder_node = '<div class="iconsheet icon_builder"></div>';
                    let resources = args.args;
                    debug('produced',resources);

                    //dojo.removeClass('production','forcehidden');
                    //dojo.empty('production');

                    for(let i=0;i<resources.bricks;i++) {
                        dojo.place(brick_node,'production');
                    }
                    for(let i=0;i<resources.civilians;i++) {
                        dojo.place(civilian_node,'production');
                    }
                    for(let i=0;i<resources.builders;i++) {
                        dojo.place(builder_node,'production');
                    }
                }
                break;

                case 'chooseGeneratedAttributes':
                    let options = args
                    if(options.length===0) {
                        debug("options.length",options.length)
                        this.ajaxcall("/hadrianswall/hadrianswall/chooseAttribute.html",
                        {attribute:'none'},this,function(result){});        
                    }
                break;

                case 'choosePathCard':
                    {
                        let cards = args.args;
                        debug("cards",cards);
                        let card1_node = '<div id="card_choice_1" class="playercardsheet card_in_hand"></div>';
                        let card2_node = '<div id="card_choice_2" class="playercardsheet card_in_hand"></div>';
                        this.player_card=[cards[0].card,cards[1].card];
                
                        dojo.removeClass('hand','forcehidden');
                        dojo.empty('hand');
                        dojo.place(card1_node,'hand');
                        dojo.addClass("card_choice_1",cards[0].card);                        
                        dojo.query('#card_choice_1').connect('onclick',this,'actHandCardChosen');

                        dojo.place(card2_node,'hand');
                        dojo.addClass("card_choice_2",cards[1].card);
                        dojo.query('#card_choice_2').connect('onclick',this,'actHandCardChosen');
                    }
                break;

                case 'useResources': {
                    let valid_moves = args.args;
                    this.updateValidMoves(valid_moves);
                }
                break;

                case 'rewardChoice': {
                }
                break;

                case 'displayAttack':
                    dojo.removeClass('attack','forcehidden');
                    let attacks = args.args;

                    ['left','center','right'].forEach((pos)=>{
                        let rank = 'attack_first_card';
                        attacks[pos].forEach((card)=>{
                            dojo.place(`<div id="fate_card" class="fatecardsheet ${card} ${rank}"></div>`,`attack_${pos}`);
                            rank = 'attack_card';
                        })
                    });



                    debug("attacks",args.args);
                break;

                case 'gainValourAndDisdain':
                    debug("args",args);
                    let valid_moves = args.args;
                    this.updateValidMoves(valid_moves);
                    dojo.addClass('attack','forcehidden');
                    dojo.empty('attack_left');
                    dojo.empty('attack_center');
                    dojo.empty('attack_right');
                break;
           
            case 'dummmy':
                break;
            }
        },

        onLeavingState: function( stateName )
        {
            debug('Leaving state',stateName);
            
            switch( stateName )
            {            
                case 'acceptFateResources':
                    dojo.addClass('fate','forcehidden');
                break;

                case 'acceptProducedResources':
                    debug("adding forcehidden");
                    dojo.addClass('production','forcehidden');
                    dojo.empty('production');
                break;

                case 'choosePathCard':
                    dojo.addClass('hand','forcehidden');
                break;           
           
            case 'dummmy':
                break;
            }               
        }, 

        resourceIconString: function(resources) {
            let res = ``;
            ['soldiers','builders','servants','civilians','bricks','renown','valour','piety','discipline'].forEach((r,a)=>{
                for(let i=0;i<resources[r];i++) {
                    res+=`<div class="iconsheet icon_${a<5?r.slice(0,-1):r} microicon"></div>`;
                }
            })

            return res;
        },

        // onUpdateActionButtons: in this method you can manage "action buttons" that are displayed in the
        //                        action status bar (ie: the HTML links in the status bar).
        //        
        onUpdateActionButtons: function( stateName, args )
        {
            debug( 'onUpdateActionButtons', stateName );                      
            if( this.isCurrentPlayerActive() )
            {            
                switch( stateName )
                {
                    case 'acceptFateResources': {
                        debug("fate resources: ",args);
                        let icons = this.resourceIconString(args);

                        this.addActionButton( 'acceptFate', icons, 'actFateResources' );

                    }
                    break;

                    case 'acceptProducedResources': {
                        let icons = this.resourceIconString(args);
                        this.addActionButton( 'acceptProducedResources', icons, 'actProducedResources' );
                    }
                    break;

                    case 'chooseGeneratedAttributes': {

                        let options = args
                        options.forEach((option)=>{
                            console.log(`Option ${option}`)
                            this.addActionButton( `${option}`,`<div id='${option}' class='iconsheet icon_${option} miniicon'></div>`,'actChooseAttribute');
                        })

                        // this.addActionButton( 'renown',"<div id='renown' class='iconsheet icon_renown miniicon'></div>",'actChooseAttribute');
                        // this.addActionButton( 'piety',"<div id='piety' class='iconsheet icon_piety miniicon'></div>",'actChooseAttribute');
                        // this.addActionButton( 'valour',"<div id='valour' class='iconsheet icon_valour miniicon'></div>",'actChooseAttribute');
                        // this.addActionButton( 'discipline',"<div id='discipline' class='iconsheet icon_discipline miniicon'></div>",'actChooseAttribute');

                    }
                    break;
                    
                    case 'choosePathCard': {
                        debug("args",args);


                        let name = [`${args[0]['name']}   `,`${args[1]['name']}   `];
                        let res = [``,``];    
                        for(let j=0;j<2;j++){
                            ['soldiers','builders','servants','civilians','bricks'].forEach((r)=>{
                                for(let i=0;i<args[1-j][r];i++) {
                                    res[j]+=`<div class="iconsheet icon_${r.slice(0,-1)} microicon"></div>`;
                                }
                            })
                        }

                        let label = [name[0]+`&nbsp; + &nbsp;`+res[0]+`&nbsp;`,`&nbsp;`+res[1]+`&nbsp; + &nbsp;`+name[1]];


                        this.addActionButton( 'hand_card_1', label[0], 'actHandCardChosen' );
                        this.addActionButton( 'hand_card_2', label[1], 'actHandCardChosen' );
                    }
                    break;

                    case 'useResources':
                        //this.addActionButton( 'undo', _('Undo'), 'actTurnUndo' );
                        //this.addActionButton( 'reset', _('Reset Turn'), 'actTurnReset' );
                        this.addActionButton( 'done', _('End Turn'), 'actTurnDone' );
                    break;
                    
                    case 'rewardChoice':
                        debug("choice args",args['choices']);

                        args['choices'].forEach((reward)=>{
                            debug("choice ",reward);
                            let resources = [];
                            resources[reward]=1;
                            let icons = this.resourceIconString(resources);
                            debug("choice ",icons);
                            this.addActionButton( `${reward}`, icons, 'actRewardChoice' );
                        });
                    break;

                    case 'displayAttack':
                        this.addActionButton( 'applyCohorts', _('Apply Cohorts'), 'actApplyCohorts' );
                    break;

                    case 'gainValourAndDisdain':
                        this.addActionButton( 'accept', _('Done'), 'actAttackResults');
                    break;


                }
            }
        },        

        ///////////////////////////////////////////////////
        //// Utility methods
        addScratchLocations: function() {
            console.log("Adding scratch loctions");
            Object.keys(scratch_data).forEach((zone)=>{
                scratch_data[zone].forEach((cord,i)=>{
                    let id = `${zone}_${i+1}`;
                    let scratch = this.format_block('jstpl_scratch',{id: id, value: "", ...cord});
                    dojo.place(scratch, `sheet${cord.s}`);
                })
            })
        },

        drawScratch: function (zone, value) {
            let id = `${zone}_${value}`;
            if(dojo.query(`#${id}`).length==0){
                console.log(`Didn't find node for ${id}`);
                return;
            }

            if(dojo.hasClass(id,'circled')){
                dojo.removeClass(id,'circledashed');
                dojo.addClass(id,'circleoutlined');
            } else {
                dojo.removeClass(id,'outlined');
                dojo.addClass(id,'scratch');
            }
        },

        drawNumber: function (zone, value) {
            let id = `${zone}`;
            if(dojo.query(`#${id}`).length==0){
                console.log(`Didn't find node for ${id}`);
                return;
            }

            if(value>0) {
                dojo.removeClass(id,'rect');
                dojo.byId(`${id}`).innerHTML = value;
            }
        },

        drawScratches: function (zone, value) {
            if(value>0) {
                console.log(`Scratching ${zone} to ${value}`);
                for(var i=1; i<=value; i++){
                    this.drawScratch(zone,i);
                }
            }
        },

        drawRoundNumbers: function (zone, values) {
            values.forEach((value,i)=>{
                //debug("ROUND NUMBERS",`Writing value ${value} in ${zone}`);
                this.drawNumber(`${zone}_${i+1}`,value);
            })
        },

        drawAllScratches: function (board) {
            //debug('DRAWING ALL SCRATCHS',board);
            Object.keys(board).forEach(key=>{
                if(key==="player_id" || key==="round") {
                    return;
                }
                if(key.slice(-7)=="_rounds") {
                    //debug("ROUND NUMBERS","Round Numbers Detected");
                    this.drawRoundNumbers(key,board[key]);
                } else {
                    this.drawScratches(key,board[key]);
                }
            })
        },

        updateValidMoves: function(valid_moves) {
            debug("valid_moves",valid_moves);
            dojo.query('.clickable').removeClass('valid');
            this.hasValidMoves = false;
            valid_moves.forEach((box)=>{
                this.hasValidMoves = true;
                let id = box.id;
                dojo.query(`#${id}`).addClass('valid');

                if(box.spend_choice) {
                    let section = id.split("_").slice(0,-1).join("_");
                    let index = parseInt(id.split("_").pop()) - 1;
                    let choices = box.spend_choice;
                    // update box data with spend choices
                    debug("spend_choice",`section ${section} index ${index} choices ${choices}`);
                    scratch_data[section][index]['spendChoices']=choices;
                }

                if(box.reward_choice) {
                    let section = id.split("_").slice(0,-1).join("_");
                    let index = parseInt(id.split("_").pop()) - 1;
                    let choices = box.reward_choice;
                    // update box data with reward choices
                    debug("reward_choice",`section ${section} index ${index} choices ${choices}`);
                    scratch_data[section][index]['rewardChoices']=choices;
                }
            });
        },

        updateResources: function(resources) {
            [`civilians`,`servants`,`soldiers`,`builders`,`bricks`].forEach(resource=>{
                if(resources[resource]) {
                    this[`${resource}_resource`].setValue( Math.abs(resources[resource]) );
                }
            });

            debug("special resources",resources['special']);
            if(resources['special'].length>0 && resources['special'][0].length>0) {
                //dojo.query("#resource_display").addClass('forcehidden');
                let speical_display = dojo.query("#speical_display");
                speical_display.removeClass('forcehidden');
                speical_display.empty();

                let first = true;
                resources['special'].forEach((resource)=>{
                    dojo.place(`<div class="iconsheet icon_${resource} miniicon ${first?"glow":""}"></div>`,"speical_display");
                    first = false;
                });
            
            } else {
                dojo.query("#resource_display").removeClass('forcehidden');
                dojo.query("#speical_display").addClass('forcehidden');                
            }
        },

        updateScoreColumn: function(score_column) {
            debug("score_column",score_column);
            let score_column_keys = ['renown','piety','valour','discipline','path','disdain','total'];
            score_column_keys.forEach((row)=>{
                let dom_id = `score_${row}`;
                this[dom_id].setValue(Math.abs(parseInt(score_column[row])));
            })

            let disdain_class = `disdain_circle_${Math.abs(score_column['disdain'])}`;
            debug("disdain_class",disdain_class);
            let disdain_circle = 
            dojo.query("#disdain_circle").removeClass();
            dojo.query("#disdain_circle").addClass(disdain_class);
            debug("disdain_circle",dojo.query("#disdain_circle"));

            try { // because sometimes scoreCtrl isn't ready when updateScoreColumn is called
                let current_player_id = this.player_id;
                let score_display = this.scoreCtrl[current_player_id];
                let score = parseInt(score_column['total']);
                score_display.toValue(score);
            } catch(e) {}
        },

        ///////////////////////////////////////////////////
        //// Player's action
        onChoiceMade: function ( evt ) {
            dojo.stopEvent(evt);
            debug("Choice Made Event",evt);

            let id = evt.target.id;
            let parts = id.split("__");
            debug("parts",parts);
            section = parts[0];
            spend = parts[1];

            dojo.query("#sheet1_selector").addClass("forcehidden");
            dojo.query("#sheet2_selector").addClass("forcehidden");

            if(this.checkAction('checkNextBox')){
                console.log(`ajax for ${section} spending ${spend}`);
                this.ajaxcall("/hadrianswall/hadrianswall/checkNextBox.html",{section,spend},this,function(result){});
            }
        },

        // TODO: For reward, have that come back with options for the user to select

        onChoiceBoxClicked: function ( evt ) {
            dojo.stopEvent(evt);
            
            let section = evt.target.id.split("_").slice(0,-1).join("_");
            let boxData = this.boxData(evt.target.id);
            debug("Choice Box clicked",section);
            debug("evt",evt);
            debug("loc",[evt.target.offsetLeft,evt.target.offsetTop]);

            let choices = boxData.spendChoices;
            let secondChoice = boxData.secondChoice
            debug("Box Data Choice Options ",choices);

            dojo.query(`#sheet${boxData.s}_selector`).empty();
            dojo.query(`#sheet${boxData.s}_selector`).removeClass("forcehidden");
            dojo.query(`#sheet${boxData.s}_selector`).style({left:`${evt.target.offsetLeft}px`,top:`${evt.target.offsetTop}px`});

            let n = dojo.place(`<div id=${section}__${choices[0]} class="iconsheet icon_${choices[0]}"></div>`,`sheet${boxData.s}_selector`);
            dojo.query(n).connect('onclick',this,'onChoiceMade');

            choices.forEach((choice,i)=>{
                if(i==0) return;
                dojo.place(`<span style="color: white; font-size: 20px">or</span>`,`sheet${boxData.s}_selector`);
                n = dojo.place(`<div id=${section}__${choice} class="iconsheet icon_${choice}"></div>`,`sheet${boxData.s}_selector`);
                dojo.query(n).connect('onclick',this,'onChoiceMade');
            })


            //this.addActionButton( 'test1', _('Test Hello'), 'actTurnDone' );

            //this.onBoxClicked(evt);
        },

        onBoxClicked: function( evt ){   
            dojo.stopEvent(evt);

            let section = evt.target.id.split("_").slice(0,-1).join("_");
            let boxData = this.boxData(evt.target.id);
            debug("Box clicked",section);
            debug("Box Data",boxData);

            if(section=="closed") {
                return;
            }

            // TODO - use the stored options that come back in valid moves

            let presentChoice = false;
            if(boxData.spendChoices) {
                presentChoice = true;

                // Make sure we can select the box at all
                if(!dojo.hasClass(`${evt.target.id}`,'valid')) {
                    presentChoice = false;
                }

                let choiceCount = boxData.spendChoices.length;
                boxData.spendChoices.forEach((choice)=>{
                    // Make sure there's a reason to present a choice (i.e. we have more than one option)
                    if(this[`${choice}_resource`].getValue()==0) {
                        choiceCount--;
                    }
                })

                // TODO
                // handle 2nd choice here


                if(choiceCount<2) {
                    presentChoice = false;
                }
            }

            if(presentChoice) {
                this.onChoiceBoxClicked(evt);
            } else {
                if(this.checkAction('checkNextBox')){
                    console.log("ajax call with ",section);
                    this.ajaxcall("/hadrianswall/hadrianswall/checkNextBox.html",{section},this,function(result){});
                }
    
                if(section=="approve") { // allow passthrough to mark disdain
                    section = "disdain";
                    if(this.checkAction('checkNextBox')){
                        console.log("ajax call with ",section);
                        this.ajaxcall("/hadrianswall/hadrianswall/checkNextBox.html",{section},this,function(result){});
                    }
                }
            }

        },

        // action responses
        actFateResources: function( evt ) {
            dojo.stopEvent( evt );
            debug("Accept Fate Resources",evt)

            if(this.checkAction('acceptFateResources')){
                this.ajaxcall("/hadrianswall/hadrianswall/acceptFateResources.html",
                    {},this,function(result){});
            }
        },

        actProducedResources: function( evt ) {
            dojo.stopEvent( evt );
            debug("Accept Produced Resources",evt)

            if(this.checkAction('acceptProducedResources')){
                this.ajaxcall("/hadrianswall/hadrianswall/acceptProducedResources.html",
                    {},this,function(result){});
            }
        },

        actChooseAttribute:  function( evt ) {
            dojo.stopEvent( evt );
            let attribute = evt.target.id;
            debug("Chosen Attribute ",attribute);
            debug("Choose Attribute evt",evt);

            if(this.checkAction('chooseAttribute')){
                this.ajaxcall("/hadrianswall/hadrianswall/chooseAttribute.html",
                    {
                        attribute
                    },this,function(result){});
            }
        },

        actHandCardChosen: function( evt ) {
            dojo.stopEvent( evt );
            let card_id = evt.target.id;
            debug("Choose card id",card_id);
            debug("Choose Card evt",evt);

            let card=this.player_card[1];
            if(card_id.slice(-1)==="1") {                
                card=this.player_card[0];
            }
            debug("picked",card);

            if(this.checkAction('choosePathCard')){
                this.ajaxcall("/hadrianswall/hadrianswall/choosePathCard.html",
                    {
                        card
                    },this,function(result){});
            }
        },

        actTurnUndo: function( evt ) {
            dojo.stopEvent( evt );
            debug("Undo",evt)

            if(this.checkAction('undoCheck')){
                this.ajaxcall("/hadrianswall/hadrianswall/undoCheck.html",
                    {},this,function(result){});
            }
        },

        actTurnReset: function( evt ) {
            dojo.stopEvent( evt );
            debug("Reset",evt)

            if(this.checkAction('restartRound')){
                this.ajaxcall("/hadrianswall/hadrianswall/restartRound.html",
                    {},this,function(result){});
            }
        },

        actTurnDone: function( evt ) {
            dojo.stopEvent( evt );
            debug("Done",evt)

            if(this.hasValidMoves) {
                this.confirmationDialog(_('You have valid moves left, are you sure you want to end your turn?'), () => {
                    if(this.checkAction('endTurn')){
                        this.ajaxcall("/hadrianswall/hadrianswall/endTurn.html",
                            {},this,function(result){});
                    }
                }); 
                return;
            } else {
                if(this.checkAction('endTurn')){
                    this.ajaxcall("/hadrianswall/hadrianswall/endTurn.html",
                        {},this,function(result){});
                }
            }

        },

        actRewardChoice: function( evt ) {
            dojo.stopEvent( evt );
            debug("Reward Choice evt",evt)
            let choice = evt.target.parentNode.id;
            debug("Reward Choice", choice);

            if(this.checkAction('rewardChoice')){
                this.ajaxcall("/hadrianswall/hadrianswall/rewardChoice.html",
                    {choice},this,function(result){});
            }
        },

        actApplyCohorts: function( evt ) {
            dojo.stopEvent( evt );
            debug("Accept attack results",evt)

            if(this.checkAction('applyCohorts')){
                this.ajaxcall("/hadrianswall/hadrianswall/applyCohorts.html",
                    {},this,function(result){});
            }
        },

        actAttackResults: function( evt ) {
            dojo.stopEvent( evt );
            debug("Accept attack results",evt)

            if(this.hasValidMoves) {
                this.showMessage("You must apply valour and/or disdain.","info");
            } else {
                if(this.checkAction('acceptAttackResults')){
                    this.ajaxcall("/hadrianswall/hadrianswall/acceptAttackResults.html",
                        {},this,function(result){});
                }
            }
        },


        /* Example:
        
        onMyMethodToCall1: function( evt )
        {
            console.log( 'onMyMethodToCall1' );
            
            // Preventing default browser reaction
            dojo.stopEvent( evt );

            // Check that this action is possible (see "possibleactions" in states.inc.php)
            if( ! this.checkAction( 'myAction' ) )
            {   return; }

            this.ajaxcall( "/hadrianswall/hadrianswall/myAction.html", { 
                                                                    lock: true, 
                                                                    myArgument1: arg1, 
                                                                    myArgument2: arg2,
                                                                    ...
                                                                 }, 
                         this, function( result ) {
                            
                            // What to do after the server call if it succeeded
                            // (most of the time: nothing)
                            
                         }, function( is_error) {

                            // What to do after the server call in anyway (success or failure)
                            // (most of the time: nothing)

                         } );        
        },        
        
        */

        
        ///////////////////////////////////////////////////
        //// Reaction to cometD notifications

        /*
            setupNotifications:
            
            In this method, you associate each of your game notifications with your local method to handle it.
            
            Note: game notification names correspond to "notifyAllPlayers" and "notifyPlayer" calls in
                  your hadrianswall.game.php file.

            // Example 1: standard notification handling
            // dojo.subscribe( 'cardPlayed', this, "notif_cardPlayed" );
            
            // Example 2: standard notification handling + tell the user interface to wait
            //            during 3 seconds after calling the method in order to let the players
            //            see what is happening in the game.
            // dojo.subscribe( 'cardPlayed', this, "notif_cardPlayed" );
            // this.notifqueue.setSynchronous( 'cardPlayed', 3000 );
            // 
                  
        */
        setupNotifications: function()
        {
            log('setupNotifications', 'notifications subscriptions setup' );
            
            dojo.subscribe( 'newRound', this, "notif_newRound");
            dojo.subscribe( 'sheetsUpdated', this, "notif_sheetsUpdated");
            dojo.subscribe( 'resourcesUpdated', this, "notif_resourcesUpdated");
            dojo.subscribe( 'pathsUpdated', this, "notif_pathsUpdated");
            dojo.subscribe( 'attack', this, "notif_attacked");
        },  

        notif_newRound: function(notif) {
            debug('notif_newRound',notif);
            debug('args',notif.args);
        },

        notif_sheetsUpdated: function(notif) {
            debug('notif_sheetsUpdated',notif);
            let board = notif.args.board;
            let valid_moves = notif.args.valid_moves;
            let resources = notif.args.resources;
            let score_column = notif.args.score_column;

            this.updateValidMoves(valid_moves);
            this.drawAllScratches(board);
            this.updateResources(resources);
            this.updateScoreColumn(score_column);
        },

        notif_resourcesUpdated: function(notif) {
            debug('notif_resourcesUpdated',notif);
            let resources = notif.args.resources;
            debug('resources',resources);

            this.updateResources(resources);
        },

        notif_attacked: function(notif) {
            debug('notif_attacked',notif);
            let attacks = notif.args;
            debug('attacked',attacks);


        },

        updatePaths: function(paths) {
            debug('paths',paths);

            let player_id = this.player_id;
            let player_color = this.gamedatas.players[player_id].color;

            for(let i=1;i<=6 && i<=paths.length;i++) {
                let path_card = paths[i-1];
                debug('path_card',path_card);
                debug('node',`#path${i}_${player_color}`);
    
                let node = dojo.query(`#path${i}_${player_color}`);
                node.removeClass(`player_back_${player_color} card_top_only`);
                node.addClass(`${path_card} card_top_only`); 
                //this.addTooltip( `path${i}_${player_color}`, _( "This is a path" ), _( "Click to highlight relivant areas." ) );
            }
        },

        notif_pathsUpdated: function(notif) {
            debug('notif_pathsUpdated',notif);
            let paths = notif.args.paths;
            this.updatePaths(paths);
        },

        boxData: function(id) {
            const lastIndex = id.lastIndexOf('_');
            const section = id.slice(0, lastIndex);
            const index = parseInt(id.slice(lastIndex + 1))-1;
            debug(`box data lookup for ${section} ${index}`);
            return scratch_data[section][index];
        }
   });             
});



let scratch_data = {
    left_cohort:[
        {s:1,x:128,y:24,w:16,h:17,c:'rect'},
        {s:1,x:148,y:24,w:16,h:17,c:'rect'},
        {s:1,x:168,y:24,w:16,h:17,c:'rect'},
        {s:1,x:187,y:24,w:16,h:17,c:'rect'},
        {s:1,x:206,y:24,w:16,h:17,c:'rect'},
        {s:1,x:225,y:24,w:16,h:17,c:'rect'}
      ],
      center_cohort:[
        {s:1,x:355,y:23,w:16,h:17,c:'rect'},
        {s:1,x:375,y:23,w:16,h:17,c:'rect'},
        {s:1,x:395,y:23,w:16,h:17,c:'rect'},
        {s:1,x:414,y:23,w:16,h:17,c:'rect'},
        {s:1,x:433,y:23,w:16,h:17,c:'rect'},
        {s:1,x:452,y:23,w:16,h:17,c:'rect'}
      ],
      right_cohort:[
        {s:1,x:582,y:21,w:16,h:17,c:'rect'},
        {s:1,x:602,y:21,w:16,h:17,c:'rect'},
        {s:1,x:621,y:21,w:16,h:17,c:'rect'},
        {s:1,x:641,y:21,w:16,h:17,c:'rect'},
        {s:1,x:660,y:21,w:16,h:17,c:'rect'},
        {s:1,x:679,y:21,w:16,h:17,c:'rect'}
      ],
      mining_and_foresting:[
        {s:1,x:90,y:75,w:16,h:17,c:'rect'},
        {s:1,x:137,y:75,w:36,h:17,c:'rect'},
        {s:1,x:206,y:75,w:16,h:17,c:'rect'},
        {s:1,x:254,y:75,w:16,h:17,c:'rect'},
        {s:1,x:300,y:75,w:36,h:17,c:'rect'},
        {s:1,x:356,y:75,w:16,h:17,c:'rect'},
        {s:1,x:392,y:75,w:16,h:17,c:'rect'},
        {s:1,x:428,y:75,w:36,h:17,c:'rect'},
        {s:1,x:484,y:75,w:16,h:17,c:'rect'},
        {s:1,x:520,y:75,w:16,h:17,c:'rect'},
        {s:1,x:557,y:75,w:36,h:17,c:'rect'},
        {s:1,x:613,y:75,w:16,h:17,c:'rect'},
        {s:1,x:649,y:75,w:16,h:17,c:'rect'},
        {s:1,x:685,y:75,w:36,h:17,c:'rect'}
      ],
      wall_guard:[
        {s:1,x:81,y:122,w:16,h:17,c:'rect'},
        {s:1,x:117,y:122,w:16,h:17,c:'rect'},
        {s:1,x:153,y:122,w:16,h:17,c:'rect'},
        {s:1,x:190,y:122,w:16,h:17,c:'rect'},
        {s:1,x:225,y:122,w:16,h:17,c:'rect'},
        {s:1,x:262,y:122,w:16,h:17,c:'rect'},
        {s:1,x:302,y:122,w:16,h:17,c:'rect'},
        {s:1,x:338,y:122,w:16,h:17,c:'rect'},
        {s:1,x:373,y:122,w:16,h:17,c:'rect'},
        {s:1,x:410,y:122,w:16,h:17,c:'rect'},
        {s:1,x:446,y:122,w:16,h:17,c:'rect'},
        {s:1,x:482,y:122,w:16,h:17,c:'rect'},
        {s:1,x:521,y:122,w:16,h:17,c:'rect'},
        {s:1,x:558,y:122,w:16,h:17,c:'rect'},
        {s:1,x:594,y:122,w:16,h:17,c:'rect'},
        {s:1,x:631,y:122,w:16,h:17,c:'rect'},
        {s:1,x:666,y:122,w:16,h:17,c:'rect'},
        {s:1,x:702,y:122,w:16,h:17,c:'rect'}
      ],
      cippi:[
        {s:1,x:94,y:171,w:16,h:17,c:'rect'},
        {s:1,x:191,y:171,w:16,h:17,c:'rect'},
        {s:1,x:268,y:171,w:16,h:17,c:'rect'},
        {s:1,x:353,y:171,w:16,h:17,c:'rect'},
        {s:1,x:450,y:171,w:16,h:17,c:'rect'},
        {s:1,x:554,y:171,w:16,h:17,c:'rect'},
        {s:1,x:671,y:171,w:16,h:17,c:'rect'}
      ],
      wall:[
        {s:1,x:74,y:219,w:16,h:17,c:'rect'},
        {s:1,x:113,y:219,w:16,h:17,c:'rect'},
        {s:1,x:133,y:219,w:16,h:17,c:'rect'},
        {s:1,x:153,y:219,w:36,h:17,c:'rect'},
        {s:1,x:210,y:219,w:16,h:17,c:'rect'},
        {s:1,x:230,y:219,w:16,h:17,c:'rect'},
        {s:1,x:250,y:219,w:16,h:17,c:'rect'},
        {s:1,x:295,y:219,w:16,h:17,c:'rect'},
        {s:1,x:315,y:219,w:16,h:17,c:'rect'},
        {s:1,x:335,y:219,w:16,h:17,c:'rect'},
        {s:1,x:372,y:219,w:36,h:17,c:'rect'},
        {s:1,x:411,y:219,w:16,h:17,c:'rect'},
        {s:1,x:431,y:219,w:16,h:17,c:'rect'},
        {s:1,x:479,y:219,w:16,h:17,c:'rect'},
        {s:1,x:515,y:219,w:16,h:17,c:'rect'},
        {s:1,x:535,y:219,w:16,h:17,c:'rect'},
        {s:1,x:573,y:219,w:16,h:17,c:'rect'},
        {s:1,x:593,y:219,w:36,h:17,c:'rect'},
        {s:1,x:632,y:219,w:16,h:17,c:'rect'},
        {s:1,x:652,y:219,w:16,h:17,c:'rect'},
        {s:1,x:700,y:219,w:16,h:17,c:'rect'}
      ],
      fort:[
        {s:1,x:75,y:266,w:36,h:18,c:'rect'},
        {s:1,x:114,y:266,w:16,h:18,c:'rect'},
        {s:1,x:133,y:266,w:16,h:18,c:'rect'},
        {s:1,x:152,y:266,w:56,h:18,c:'rect'},
        {s:1,x:210,y:266,w:16,h:18,c:'rect'},
        {s:1,x:230,y:266,w:16,h:18,c:'rect'},
        {s:1,x:250,y:266,w:36,h:18,c:'rect'},
        {s:1,x:295,y:266,w:16,h:18,c:'rect'},
        {s:1,x:314,y:266,w:16,h:18,c:'rect'},
        {s:1,x:334,y:266,w:36,h:18,c:'rect'},
        {s:1,x:372,y:266,w:36,h:18,c:'rect'},
        {s:1,x:411,y:266,w:16,h:18,c:'rect'},
        {s:1,x:430,y:266,w:36,h:18,c:'rect'},
        {s:1,x:469,y:266,w:36,h:18,c:'rect'},
        {s:1,x:515,y:266,w:16,h:18,c:'rect'},
        {s:1,x:535,y:266,w:36,h:18,c:'rect'},
        {s:1,x:573,y:266,w:16,h:18,c:'rect'},
        {s:1,x:593,y:266,w:36,h:18,c:'rect'},
        {s:1,x:631,y:266,w:16,h:18,c:'rect'},
        {s:1,x:651,y:266,w:36,h:18,c:'rect'},
        {s:1,x:689,y:266,w:36,h:18,c:'rect'}
      ],
      granary:[
        {s:1,x:409,y:319,w:10,h:10,c:'circle'},
        {s:1,x:635,y:319,w:10,h:10,c:'circle'},
        {s:1,x:658,y:317,w:17,h:16,c:'rect'}
      ],
      renown:[
        {s:1,x:88.5,y:530.5,w:16,h:17,c:'rect',tt:"+ Citizen"},
        {s:1,x:107.5,y:530,w:16,h:17,c:'rect'},
        {s:1,x:126,y:530,w:16,h:17,c:'rect'},
        {s:1,x:145,y:530,w:16,h:17,c:'rect'},
        {s:1,x:164,y:530,w:16,h:17,c:'rect'},
        {s:1,x:184,y:530,w:16,h:17,c:'rect'},
        {s:1,x:203,y:530,w:16,h:17,c:'rect'},
        {s:1,x:222,y:530,w:16,h:17,c:'rect'},
        {s:1,x:242,y:530,w:16,h:17,c:'rect'},
        {s:1,x:261,y:530,w:16,h:17,c:'rect'},
        {s:1,x:281,y:530,w:16,h:17,c:'rect'},
        {s:1,x:300,y:530,w:16,h:17,c:'rect'},
        {s:1,x:320,y:530,w:16,h:17,c:'rect'},
        {s:1,x:339,y:530,w:16,h:17,c:'rect'},
        {s:1,x:358,y:530,w:16,h:17,c:'rect'},
        {s:1,x:378,y:530,w:16,h:17,c:'rect'},
        {s:1,x:397,y:530,w:16,h:17,c:'rect'},
        {s:1,x:416,y:530,w:16,h:17,c:'rect'},
        {s:1,x:436,y:530,w:16,h:17,c:'rect'},
        {s:1,x:455,y:530,w:16,h:17,c:'rect'},
        {s:1,x:474,y:530,w:16,h:17,c:'rect'},
        {s:1,x:494,y:530,w:16,h:17,c:'rect'},
        {s:1,x:513,y:530,w:16,h:17,c:'rect'},
        {s:1,x:532,y:530,w:16,h:17,c:'rect'},
        {s:1,x:552,y:530,w:16,h:17,c:'rect'}
      ],
      piety:[
        {s:1,x:87,y:559,w:16,h:17,c:'rect'},
        {s:1,x:107,y:559,w:16,h:17,c:'rect'},
        {s:1,x:126,y:559,w:16,h:17,c:'rect'},
        {s:1,x:145,y:559,w:16,h:17,c:'rect'},
        {s:1,x:164,y:559,w:16,h:17,c:'rect'},
        {s:1,x:184,y:559,w:16,h:17,c:'rect'},
        {s:1,x:203,y:559,w:16,h:17,c:'rect'},
        {s:1,x:222,y:559,w:16,h:17,c:'rect'},
        {s:1,x:242,y:559,w:16,h:17,c:'rect'},
        {s:1,x:261,y:559,w:16,h:17,c:'rect'},
        {s:1,x:281,y:559,w:16,h:17,c:'rect'},
        {s:1,x:300,y:559,w:16,h:17,c:'rect'},
        {s:1,x:320,y:559,w:16,h:17,c:'rect'},
        {s:1,x:339,y:559,w:16,h:17,c:'rect'},
        {s:1,x:358,y:559,w:16,h:17,c:'rect'},
        {s:1,x:378,y:559,w:16,h:17,c:'rect'},
        {s:1,x:397,y:559,w:16,h:17,c:'rect'},
        {s:1,x:416,y:559,w:16,h:17,c:'rect'},
        {s:1,x:436,y:559,w:16,h:17,c:'rect'},
        {s:1,x:455,y:559,w:16,h:17,c:'rect'},
        {s:1,x:474,y:559,w:16,h:17,c:'rect'},
        {s:1,x:494,y:559,w:16,h:17,c:'rect'},
        {s:1,x:513,y:559,w:16,h:17,c:'rect'},
        {s:1,x:532,y:559,w:16,h:17,c:'rect'},
        {s:1,x:552,y:559,w:16,h:17,c:'rect'}
      ],
      valour:[
        {s:1,x:87,y:586,w:16,h:17,c:'rect'},
        {s:1,x:107,y:586,w:16,h:17,c:'rect'},
        {s:1,x:126,y:586,w:16,h:17,c:'rect'},
        {s:1,x:145,y:586,w:16,h:17,c:'rect'},
        {s:1,x:164,y:586,w:16,h:17,c:'rect'},
        {s:1,x:184,y:586,w:16,h:17,c:'rect'},
        {s:1,x:203,y:586,w:16,h:17,c:'rect'},
        {s:1,x:222,y:586,w:16,h:17,c:'rect'},
        {s:1,x:242,y:586,w:16,h:17,c:'rect'},
        {s:1,x:261,y:586,w:16,h:17,c:'rect'},
        {s:1,x:281,y:586,w:16,h:17,c:'rect'},
        {s:1,x:300,y:586,w:16,h:17,c:'rect'},
        {s:1,x:320,y:586,w:16,h:17,c:'rect'},
        {s:1,x:339,y:586,w:16,h:17,c:'rect'},
        {s:1,x:358,y:586,w:16,h:17,c:'rect'},
        {s:1,x:378,y:586,w:16,h:17,c:'rect'},
        {s:1,x:397,y:586,w:16,h:17,c:'rect'},
        {s:1,x:416,y:586,w:16,h:17,c:'rect'},
        {s:1,x:436,y:586,w:16,h:17,c:'rect'},
        {s:1,x:455,y:586,w:16,h:17,c:'rect'},
        {s:1,x:474,y:586,w:16,h:17,c:'rect'},
        {s:1,x:494,y:586,w:16,h:17,c:'rect'},
        {s:1,x:513,y:586,w:16,h:17,c:'rect'},
        {s:1,x:532,y:586,w:16,h:17,c:'rect'},
        {s:1,x:552,y:586,w:16,h:17,c:'rect'}
      ],
      discipline:[
        {s:1,x:87,y:615,w:16,h:17,c:'rect'},
        {s:1,x:107,y:615,w:16,h:17,c:'rect'},
        {s:1,x:126,y:615,w:16,h:17,c:'rect'},
        {s:1,x:145,y:615,w:16,h:17,c:'rect'},
        {s:1,x:164,y:615,w:16,h:17,c:'rect'},
        {s:1,x:184,y:615,w:16,h:17,c:'rect'},
        {s:1,x:203,y:615,w:16,h:17,c:'rect'},
        {s:1,x:222,y:615,w:16,h:17,c:'rect'},
        {s:1,x:242,y:615,w:16,h:17,c:'rect'},
        {s:1,x:261,y:615,w:16,h:17,c:'rect'},
        {s:1,x:281,y:615,w:16,h:17,c:'rect'},
        {s:1,x:300,y:615,w:16,h:17,c:'rect'},
        {s:1,x:320,y:615,w:16,h:17,c:'rect'},
        {s:1,x:339,y:615,w:16,h:17,c:'rect'},
        {s:1,x:358,y:615,w:16,h:17,c:'rect'},
        {s:1,x:378,y:615,w:16,h:17,c:'rect'},
        {s:1,x:397,y:615,w:16,h:17,c:'rect'},
        {s:1,x:416,y:615,w:16,h:17,c:'rect'},
        {s:1,x:436,y:615,w:16,h:17,c:'rect'},
        {s:1,x:455,y:615,w:16,h:17,c:'rect'},
        {s:1,x:474,y:615,w:16,h:17,c:'rect'},
        {s:1,x:494,y:615,w:16,h:17,c:'rect'},
        {s:1,x:513,y:615,w:16,h:17,c:'rect'},
        {s:1,x:532,y:615,w:16,h:17,c:'rect'},
        {s:1,x:552,y:615,w:16,h:17,c:'rect'}
      ],
      traders:[
        {s:2,x:12,y:95,w:16,h:16,c:'rect'},
        {s:2,x:31,y:95,w:16,h:16,c:'rect'},
        {s:2,x:51,y:95,w:16,h:16,c:'rect'},
        {s:2,x:70,y:95,w:16,h:16,c:'rect'},
        {s:2,x:89,y:95,w:16,h:16,c:'rect'},
        {s:2,x:109,y:95,w:16,h:16,c:'rect'},
        {s:2,x:128,y:95,w:16,h:16,c:'rect'},
        {s:2,x:147,y:95,w:16,h:16,c:'rect'},
        {s:2,x:167,y:95,w:16,h:16,c:'rect'}
      ],
      performers:[
        {s:2,x:12,y:261,w:16,h:16,c:'rect'},
        {s:2,x:31,y:261,w:16,h:16,c:'rect'},
        {s:2,x:51,y:261,w:16,h:16,c:'rect'},
        {s:2,x:70,y:261,w:16,h:16,c:'rect'},
        {s:2,x:89,y:261,w:16,h:16,c:'rect'},
        {s:2,x:109,y:261,w:16,h:16,c:'rect'},
        {s:2,x:128,y:261,w:16,h:16,c:'rect'},
        {s:2,x:147,y:261,w:16,h:16,c:'rect'},
        {s:2,x:167,y:261,w:16,h:16,c:'rect'}
      ],
      priests:[
        {s:2,x:12,y:406,w:16,h:16,c:'rect'},
        {s:2,x:31,y:406,w:16,h:16,c:'rect'},
        {s:2,x:51,y:406,w:16,h:16,c:'rect'},
        {s:2,x:70,y:406,w:16,h:16,c:'rect'},
        {s:2,x:89,y:406,w:16,h:16,c:'rect'},
        {s:2,x:109,y:406,w:16,h:16,c:'rect'},
        {s:2,x:128,y:406,w:16,h:16,c:'rect'},
        {s:2,x:147,y:406,w:16,h:16,c:'rect'},
        {s:2,x:167,y:406,w:16,h:16,c:'rect'}
      ],
      apparitores:[
        {s:2,x:12,y:536,w:16,h:16,c:'rect'},
        {s:2,x:31,y:536,w:16,h:16,c:'rect'},
        {s:2,x:51,y:536,w:16,h:16,c:'rect'},
        {s:2,x:70,y:536,w:16,h:16,c:'rect'},
        {s:2,x:89,y:536,w:16,h:16,c:'rect'},
        {s:2,x:109,y:536,w:16,h:16,c:'rect'},
        {s:2,x:128,y:536,w:16,h:16,c:'rect'},
        {s:2,x:147,y:536,w:16,h:16,c:'rect'},
        {s:2,x:167,y:536,w:16,h:16,c:'rect'}
      ],
      patricians:[
        {s:2,x:12,y:689,w:16,h:16,c:'rect'},
        {s:2,x:31,y:689,w:16,h:16,c:'rect'},
        {s:2,x:51,y:689,w:16,h:16,c:'rect'},
        {s:2,x:70,y:689,w:16,h:16,c:'rect'},
        {s:2,x:89,y:689,w:16,h:16,c:'rect'},
        {s:2,x:109,y:689,w:16,h:16,c:'rect'},
        {s:2,x:128,y:689,w:16,h:16,c:'rect'},
        {s:2,x:147,y:689,w:16,h:16,c:'rect'},
        {s:2,x:167,y:689,w:16,h:16,c:'rect'}
      ],
      precinct:[
        {s:2,x:306,y:17,w:44,h:16,c:'rect'},
        {s:2,x:306,y:41,w:44,h:16,c:'rect'},
        {s:2,x:306,y:63,w:44,h:16,c:'rect'}
      ],
      gardens:[
        {s:2,x:424,y:317,w:66,h:16,c:'rect'},
        {s:2,x:624,y:317,w:100,h:16,c:'rect'}
      ],
      production:[
        {s:1,x:105,y:358,w:10,h:10,c:'circle'},
        {s:1,x:124,y:358,w:10,h:10,c:'circle'},
        {s:1,x:143,y:358,w:10,h:10,c:'circle'},
        {s:1,x:162,y:358,w:10,h:10,c:'circle'},
        {s:1,x:180,y:358,w:10,h:10,c:'circle'},
        {s:1,x:199,y:358,w:10,h:10,c:'circle'},
        {s:1,x:218,y:358,w:10,h:10,c:'circle'},
        {s:1,x:238,y:358,w:10,h:10,c:'circle'}
      ],
      hotel:[
        {s:1,x:126,y:410,w:10,h:10,c:'circle'},
        {s:1,x:149,y:407,w:15,h:15,c:'rect'},
        {s:1,x:126,y:431,w:10,h:10,c:'circle'},
        {s:1,x:149,y:428,w:32,h:15,c:'rect'}
      ],
      workshop:[
        {s:1,x:322,y:410,w:10,h:10,c:'circle'},
        {s:1,x:345,y:407,w:15,h:15,c:'rect'},
        {s:1,x:322,y:431,w:10,h:10,c:'circle'},
        {s:1,x:345,y:428,w:32,h:15,c:'rect'}
      ],
      road:[
        {s:1,x:514,y:410,w:10,h:10,c:'circle'},
        {s:1,x:537,y:407,w:34,h:15,c:'rect'},
        {s:1,x:514,y:431,w:10,h:10,c:'circle'},
        {s:1,x:537,y:428,w:34,h:15,c:'rect'}
      ],
      archway:[
        {s:1,x:209,y:470,w:33,h:16,c:'rect'}
      ],
      monolith:[
        {s:1,x:374,y:470,w:33,h:16,c:'rect'}
      ],
      column:[
        {s:1,x:533,y:470,w:33,h:16,c:'rect'}
      ],
      statue:[
        {s:1,x:689,y:470,w:33,h:16,c:'rect'}
      ],
      disdain:[
        {s:1,x:202,y:677,w:10,h:10,c:'circled circledashed'},
        {s:1,x:217,y:677,w:10,h:10,c:'circled circledashed'},
        {s:1,x:233,y:677,w:10,h:10,c:'circled circledashed'},
        {s:1,x:249,y:677,w:10,h:10,c:'circled circledashed'},
        {s:1,x:265,y:677,w:10,h:10,c:'circled circledashed'},
        {s:1,x:202,y:691,w:10,h:10,c:'circled circledashed'},
        {s:1,x:217,y:691,w:10,h:10,c:'circled circledashed'},
        {s:1,x:233,y:691,w:10,h:10,c:'circled circledashed'},
        {s:1,x:249,y:691,w:10,h:10,c:'circled circledashed'},
        {s:1,x:265,y:691,w:10,h:10,c:'circled circledashed'},
        {s:1,x:202,y:706,w:10,h:10,c:'circled circledashed'},
        {s:1,x:217,y:706,w:10,h:10,c:'circled circledashed'},
        {s:1,x:233,y:706,w:10,h:10,c:'circled circledashed'},
        {s:1,x:249,y:706,w:10,h:10,c:'circled circledashed'},
        {s:1,x:265,y:706,w:10,h:10,c:'circled circledashed'}
      ],
      approve:[
        {s:1,x:202,y:677,w:12,h:12,c:'circle'},
        {s:1,x:217,y:677,w:12,h:12,c:'circle'},
        {s:1,x:233,y:677,w:12,h:12,c:'circle'},
        {s:1,x:249,y:677,w:12,h:12,c:'circle'},
        {s:1,x:265,y:677,w:12,h:12,c:'circle'},
        {s:1,x:202,y:691,w:12,h:12,c:'circle'},
        {s:1,x:217,y:691,w:12,h:12,c:'circle'},
        {s:1,x:233,y:691,w:12,h:12,c:'circle'},
        {s:1,x:249,y:691,w:12,h:12,c:'circle'},
        {s:1,x:265,y:691,w:12,h:12,c:'circle'},
        {s:1,x:202,y:706,w:12,h:12,c:'circle'},
        {s:1,x:217,y:706,w:12,h:12,c:'circle'},
        {s:1,x:233,y:706,w:12,h:12,c:'circle'},
        {s:1,x:249,y:706,w:12,h:12,c:'circle'},
        {s:1,x:265,y:706,w:12,h:12,c:'circle'}
      ],
      temple:[
        {s:2,x:416,y:352,w:16,h:16,c:'rect'},
        {s:2,x:558,y:352,w:16,h:16,c:'rect'},
        {s:2,x:708,y:352,w:16,h:16,c:'rect'}
      ],
      training_grounds:[
        {s:1,x:442,y:355,w:16,h:16,c:'rect'},
        {s:1,x:478,y:355,w:16,h:16,c:'rect'},
        {s:1,x:512,y:355,w:16,h:16,c:'rect'},
        {s:1,x:548,y:355,w:16,h:16,c:'rect'},
        {s:1,x:582,y:355,w:16,h:16,c:'rect'}
      ],
      training_grounds_rounds:[
        {s:1,x:459,y:355,w:16,h:16,c:'rect roundNumber'},
        {s:1,x:495,y:355,w:16,h:16,c:'rect roundNumber'},
        {s:1,x:529,y:355,w:16,h:16,c:'rect roundNumber'},
        {s:1,x:565,y:355,w:16,h:16,c:'rect roundNumber'},
        {s:1,x:599,y:355,w:16,h:16,c:'rect roundNumber'}
      ],
      forum:[
        {s:1,x:615,y:408,w:16,h:16,c:'rect'},
        {s:1,x:615,y:429,w:16,h:16,c:'rect'},
        {s:1,x:688,y:408,w:16,h:16,c:'rect'},
        {s:1,x:688,y:429,w:16,h:16,c:'rect'}
      ],
      forum_rounds:[
        {s:1,x:632,y:408,w:16,h:16,c:'rect roundNumber'},
        {s:1,x:632,y:429,w:16,h:16,c:'rect roundNumber'},
        {s:1,x:705,y:408,w:16,h:16,c:'rect roundNumber'},
        {s:1,x:705,y:429,w:16,h:16,c:'rect roundNumber'}
      ],
      courthouse:[
        {s:2,x:588,y:459,w:16,h:17,c:'rect'}
      ],
      courthouse_c1:[
        {s:2,x:486,y:484,w:16,h:17,c:'rect'},
        {s:2,x:486,y:507,w:16,h:17,c:'rect'},
        {s:2,x:486,y:530,w:16,h:17,c:'rect'}
      ],
      courthouse_c2:[
        {s:2,x:587,y:484,w:21,h:17,c:'rect'},
        {s:2,x:587,y:507,w:21,h:17,c:'rect'},
        {s:2,x:587,y:530,w:21,h:17,c:'rect'}
      ],
      courthouse_c3:[
        {s:2,x:690,y:484,w:16,h:17,c:'rect'},
        {s:2,x:690,y:507,w:16,h:17,c:'rect'},
        {s:2,x:690,y:530,w:16,h:17,c:'rect'}
      ],
      courthouse_c1_rounds:[
        {s:2,x:503,y:484,w:16,h:17,c:'rect roundNumber'},
        {s:2,x:503,y:507,w:16,h:17,c:'rect roundNumber'},
        {s:2,x:503,y:530,w:16,h:17,c:'rect roundNumber'}
      ],
      courthouse_c2_rounds:[
        {s:2,x:609,y:484,w:16,h:17,c:'rect roundNumber'},
        {s:2,x:609,y:507,w:16,h:17,c:'rect roundNumber'},
        {s:2,x:609,y:530,w:16,h:17,c:'rect roundNumber'}
      ],
      courthouse_c3_rounds:[
        {s:2,x:707,y:484,w:16,h:17,c:'rect roundNumber'},
        {s:2,x:707,y:507,w:16,h:17,c:'rect roundNumber'},
        {s:2,x:707,y:530,w:16,h:17,c:'rect roundNumber'}
      ],
      baths:[
        {s:2,x:315,y:459,w:16,h:17,c:'rect'}
      ],
      baths_approve:[
        {s:2,x:272,y:484,w:16,h:17,c:'rect'},
        {s:2,x:272,y:507,w:16,h:17,c:'rect'},
        {s:2,x:272,y:530,w:16,h:17,c:'rect'},
        {s:2,x:392,y:484,w:16,h:17,c:'rect'},
        {s:2,x:392,y:507,w:16,h:17,c:'rect'},
        {s:2,x:392,y:530,w:16,h:17,c:'rect'}
      ],
      baths_approve_rounds:[
        {s:2,x:289,y:484,w:16,h:17,c:'rect roundNumber'},
        {s:2,x:289,y:507,w:16,h:17,c:'rect roundNumber'},
        {s:2,x:289,y:530,w:16,h:17,c:'rect roundNumber'},
        {s:2,x:409,y:484,w:16,h:17,c:'rect roundNumber'},
        {s:2,x:409,y:507,w:16,h:17,c:'rect roundNumber'},
        {s:2,x:409,y:530,w:16,h:17,c:'rect roundNumber'}
      ],
      small_temple:[
        {s:2,x:362,y:375,w:16,h:16,c:'rect'},
        {s:2,x:382,y:375,w:16,h:16,c:'rect'},
        {s:2,x:410,y:376,w:16,h:16,c:'circled circledashed'}
      ],
      small_temple_favor:[
        {s:2,x:410,y:376,w:16,h:16,c:'circle'}
      ],
      medium_temple:[
        {s:2,x:501,y:376,w:16,h:16,c:'rect'},
        {s:2,x:521,y:376,w:16,h:16,c:'rect'},
        {s:2,x:501,y:399,w:16,h:16,c:'rect'},
        {s:2,x:521,y:399,w:16,h:16,c:'rect'},
        {s:2,x:501,y:422,w:16,h:16,c:'rect'},
        {s:2,x:521,y:422,w:16,h:16,c:'rect'},
        {s:2,x:549,y:422,w:16,h:16,c:'circled circledashed'}
      ],
      medium_temple_favor:[
        {s:2,x:549,y:422,w:16,h:16,c:'circle'}
      ],
      large_temple:[
        {s:2,x:643,y:376,w:16,h:16,c:'rect'},
        {s:2,x:663,y:376,w:16,h:16,c:'rect'},
        {s:2,x:643,y:399,w:16,h:16,c:'rect'},
        {s:2,x:663,y:399,w:16,h:16,c:'rect'},
        {s:2,x:643,y:422,w:16,h:16,c:'rect'},
        {s:2,x:663,y:422,w:16,h:16,c:'rect'},
        {s:2,x:691,y:422,w:16,h:16,c:'circled circledashed'}
      ],
      large_temple_favor:[
        {s:2,x:691,y:422,w:16,h:16,c:'circle'}
      ],

      closed:[
        //{s:1,x:385,y:380,w:190,h:80,c:'donext'},  // Roads
        //{s:1,x:385+195,y:380,w:155,h:80,c:'donext'},  // Forum

        //{s:2,x:360,y: 10,w:380,h:125,c:'closed'}, // market
        //{s:2,x:190,y:145,w:550,h:160,c:'closed'}, // theatre & gladiators
        //{s:2,x:190,y:372,w:550,h: 75,c:'closed'}, // temples
        //{s:2,x:190,y:580,w:550,h:160,c:'closed'}  // diplomats & scouts
      ],

}


/*

const PLUS = 'PLUS';
const RESOURCE = 'RESOURCE';
const PRODUCTION = 'PRODUCTION';
const RESOURCE_PRODUCTION = 'PRODUCTION';
const CIVILIAN = 'CIVILIAN';
const CIVILIAN_PRODUCTION = 'CIVILIAN_PRODUCTION';
const SERVANT = 'SERVANT';
const SOLDIER = 'SOLDIER';
const BUILDER = 'BUILDER';
const BUILDER_PRODUCTION = 'BUILDER_PRODUCTION';
const RENOWN = 'RENOWN';
const PIETY = 'PIETY';
const VALOUR = 'VALOUR';
const DISCIPLINE = 'DISCIPLINE';
const ATTRIBUTE_PRODUCTION = 'ATTRIBUTE_PRODUCTION';
const TRADER = 'TRADER';
const PERFORMER = 'PERFORMER';
const PRIEST = 'PRIEST';
const APPARITOR = 'APPARITOR';
const PATRICIAN = 'PATRICIAN';
const COHORT = 'COHORT';
const DISDAIN = 'DISDAIN';
const REMOVE_DISDAIN = 'REMOVE_DISDAIN';
const RED_GLADIATOR = 'RED_GLADIATOR';
const BLUE_GLADIATOR = 'BLUE_GLADIATOR';
const TRADE_GOOD = 'TRADE_GOOD';
const SCOUT = 'SCOUT';
const SWORD = 'SWORD';


let scratch_data_old = {
    sheet1:{
        left_cohort:
        // {
        //     prereq:null,
        //     cost:[COHORT],
        //     altCost:null,
        //     boxes:
            [
                {x: 128,y:24,w:16,h:17},
                {x: 148,y:24,w:16,h:17},
                {x: 168,y:24,w:16,h:17,r:[DISCIPLINE]},
                {x: 187,y:24,w:16,h:17},
                {x: 206,y:24,w:16,h:17,r:[VALOUR]},
                {x: 225,y:24,w:16,h:17,r:[DISCIPLINE]},
            ],
        // },
        center_cohort:[
            {x: 355,y:23,w:16,h:17},
            {x: 375,y:23,w:16,h:17},
            {x: 395,y:23,w:16,h:17,r:[DISCIPLINE]},
            {x: 414,y:23,w:16,h:17},
            {x: 433,y:23,w:16,h:17,r:[VALOUR]},
            {x: 452,y:23,w:16,h:17,r:[DISCIPLINE]},
        ],
        right_cohort:[
            {x: 582,y:21,w:16,h:17},
            {x: 602,y:21,w:16,h:17},
            {x: 621,y:21,w:16,h:17,r:[DISCIPLINE]},
            {x: 641,y:21,w:16,h:17},
            {x: 660,y:21,w:16,h:17,r:[VALOUR]},
            {x: 679,y:21,w:16,h:17,r:[DISCIPLINE]},
        ],
        mining_and_foresting:[
            {x: 90,y:75,w:16,h:17},
            {x:137,y:75,w:36,h:17},
            {x:206,y:75,w:16,h:17},
            {x:254,y:75,w:16,h:17},
            {x:300,y:75,w:36,h:17},
            {x:356,y:75,w:16,h:17},
            {x:392,y:75,w:16,h:17},
            {x:428,y:75,w:36,h:17},
            {x:484,y:75,w:16,h:17},
            {x:520,y:75,w:16,h:17},
            {x:557,y:75,w:36,h:17},
            {x:613,y:75,w:16,h:17},
            {x:649,y:75,w:16,h:17},
            {x:685,y:75,w:36,h:17},
        ],
        wall_guard:[
            {x: 81,y:122,w:16,h:17,r:[COHORT]},
            {x:117,y:122,w:16,h:17,r:[DISCIPLINE]},
            {x:153,y:122,w:16,h:17,r:[COHORT]},
            {x:190,y:122,w:16,h:17},            
            {x:225,y:122,w:16,h:17},            
            {x:262,y:122,w:16,h:17,r:[COHORT]},
            {x:302,y:122,w:16,h:17},            
            {x:338,y:122,w:16,h:17},            
            {x:373,y:122,w:16,h:17,r:[COHORT]},
            {x:410,y:122,w:16,h:17},            
            {x:446,y:122,w:16,h:17},            
            {x:482,y:122,w:16,h:17,r:[COHORT]},
            {x:521,y:122,w:16,h:17},            
            {x:558,y:122,w:16,h:17},            
            {x:594,y:122,w:16,h:17,r:[COHORT]},
            {x:631,y:122,w:16,h:17},            
            {x:666,y:122,w:16,h:17},            
            {x:702,y:122,w:16,h:17,r:[COHORT]},
        ],
        cippi:[
            {x: 94,y:171,w:16,h:17},
            {x:191,y:171,w:16,h:17},
            {x:268,y:171,w:16,h:17,r:[COHORT]},
            {x:353,y:171,w:16,h:17,r:[CIVILIAN]},
            {x:450,y:171,w:16,h:17,r:[COHORT]},
            {x:554,y:171,w:16,h:17,r:[RENOWN]},
            {x:671,y:171,w:16,h:17,r:[COHORT]},
        ],
        wall:[
            {x: 74,y:219,w:16,h:17,r:[CIVILIAN]},
            {x:113,y:219,w:16,h:17},
            {x:133,y:219,w:16,h:17,r:[CIVILIAN]},
            {x:153,y:219,w:36,h:17,r:[RENOWN,COHORT]},
            {x:210,y:219,w:16,h:17,r:[CIVILIAN]},
            {x:230,y:219,w:16,h:17},
            {x:250,y:219,w:16,h:17,r:[COHORT]},
            {x:295,y:219,w:16,h:17,r:[RENOWN]},
            {x:315,y:219,w:16,h:17,r:[CIVILIAN]},
            {x:335,y:219,w:16,h:17},
            {x:372,y:219,w:36,h:17,r:[RENOWN,COHORT]},
            {x:411,y:219,w:16,h:17},
            {x:431,y:219,w:16,h:17,r:[CIVILIAN]},
            {x:479,y:219,w:16,h:17,r:[COHORT]},
            {x:515,y:219,w:16,h:17,r:[RENOWN]},
            {x:535,y:219,w:16,h:17,r:[CIVILIAN]},
            {x:573,y:219,w:16,h:17},
            {x:593,y:219,w:36,h:17,r:[RENOWN,COHORT]},
            {x:632,y:219,w:16,h:17,r:[CIVILIAN]},
            {x:652,y:219,w:16,h:17},
            {x:700,y:219,w:16,h:17,r:[COHORT]},
        ],
        fort:[
            {x: 75,y:266,w:36,h:18},
            {x:114,y:266,w:16,h:18},
            {x:133,y:266,w:16,h:18},
            {x:152,y:266,w:56,h:18,r:[CIVILIAN]},
            {x:210,y:266,w:16,h:18},
            {x:230,y:266,w:16,h:18,r:[CIVILIAN]},
            {x:250,y:266,w:36,h:18,r:[VALOUR]},

            {x:295,y:266,w:16,h:18,r:[CIVILIAN]},
            {x:314,y:266,w:16,h:18},
            {x:334,y:266,w:36,h:18,r:[CIVILIAN]},
            {x:372,y:266,w:36,h:18},
            {x:411,y:266,w:16,h:18,r:[CIVILIAN]},
            {x:430,y:266,w:36,h:18},
            {x:469,y:266,w:36,h:18,r:[VALOUR,COHORT]},

            {x:515,y:266,w:16,h:18},
            {x:535,y:266,w:36,h:18,r:[CIVILIAN]},
            {x:573,y:266,w:16,h:18},
            {x:593,y:266,w:36,h:18,r:[CIVILIAN]},
            {x:631,y:266,w:16,h:18},
            {x:651,y:266,w:36,h:18,r:[CIVILIAN]},
            {x:689,y:266,w:36,h:18,r:[VALOUR,COHORT]},
        ],
        granery:[
            {x:409,y:319,w:10,h:10,class:'circle'},
            {x:635,y:319,w:10,h:10,class:'circle'},
            {x:658,y:317,w:17,h:16},
        ],
        resource_production:[
            {x:105,y:358,w:10,h:10,class:'circle'},
            {x:124,y:358,w:10,h:10,class:'circle'},
            {x:143,y:358,w:10,h:10,class:'circle'},
            {x:162,y:358,w:10,h:10,class:'circle'},
            {x:180,y:358,w:10,h:10,class:'circle'},
            {x:199,y:358,w:10,h:10,class:'circle'},
            {x:218,y:358,w:10,h:10,class:'circle'},
            {x:238,y:358,w:10,h:10,class:'circle'},
        ],
        training_grounds:[
            {x:442,y:355,w:16,h:16},
            {x:478,y:355,w:15,h:16},
            {x:512,y:355,w:16,h:16},
            {x:548,y:355,w:16,h:16},
            {x:582,y:355,w:16,h:16},
        ],
        training_grounds_l1:[
            {x:463,y:355,w:13,h:16,class:'roundNumber',value:1},
        ],
        training_grounds_l2:[
            {x:498,y:355,w:13,h:16,class:'roundNumber',value:2},
        ],
        training_grounds_l3:[
            {x:533,y:355,w:13,h:16,class:'roundNumber',value:3},
        ],
        training_grounds_l4:[
            {x:569,y:355,w:13,h:16,class:'roundNumber',value:4},
        ],
        training_grounds_l5:[
            {x:603,y:355,w:13,h:16,class:'roundNumber',value:5},
        ],
        hotel:[
            {x:126,y:410,w:10,h:10,class:'circle'},
            {x:149,y:407,w:15,h:15},
            {x:126,y:431,w:10,h:10,class:'circle'},
            {x:149,y:428,w:32,h:15},
        ],
        workshop:[
            {x:322,y:410,w:10,h:10,class:'circle'},
            {x:345,y:407,w:15,h:15},
            {x:322,y:431,w:10,h:10,class:'circle'},
            {x:345,y:428,w:32,h:15},
        ],
        road:[
            {x:514,y:410,w:10,h:10,class:'circle'},
            {x:537,y:407,w:34,h:15},
            {x:514,y:431,w:10,h:10,class:'circle'},
            {x:537,y:428,w:34,h:15},
        ],
        forum:[
            {x:615,y:408,w:16,h:16},
            {x:615,y:429,w:16,h:16},
            {x:688,y:408,w:16,h:16},
            {x:688,y:429,w:16,h:16},
        ],
        forum_1:[
            {x:636,y:408,w:13,h:17,class:'roundNumber',value:5},
        ],
        forum_2:[
            {x:636,y:429,w:13,h:17,class:'roundNumber',value:5},
        ],
        forum_3:[
            {x:709,y:408,w:13,h:17,class:'roundNumber',value:5},
        ],
        forum_4:[
            {x:709,y:429,w:13,h:17,class:'roundNumber',value:5},
        ],
        archway:[
            {x:209,y:470,w:33,h:16},
        ],
        monolith:[
            {x:374,y:470,w:33,h:16},
        ],
        column:[
            {x:533,y:470,w:33,h:16},
        ],
        statue:[
            {x:689,y:470,w:33,h:16},
        ],
        renown:[
            {x: 87,y:530,w:16,h:17},
            {x:107,y:530,w:16,h:17},
            {x:126,y:530,w:16,h:17},
            {x:145,y:530,w:16,h:17},
            {x:164,y:530,w:16,h:17},
            {x:184,y:530,w:16,h:17},
            {x:203,y:530,w:16,h:17},
            {x:222,y:530,w:16,h:17},
            {x:242,y:530,w:16,h:17},
            {x:261,y:530,w:16,h:17},
            {x:281,y:530,w:16,h:17},
            {x:300,y:530,w:16,h:17},
            {x:320,y:530,w:16,h:17},
            {x:339,y:530,w:16,h:17},
            {x:358,y:530,w:16,h:17},
            {x:378,y:530,w:16,h:17},
            {x:397,y:530,w:16,h:17},
            {x:416,y:530,w:16,h:17},
            {x:436,y:530,w:16,h:17},
            {x:455,y:530,w:16,h:17},
            {x:474,y:530,w:16,h:17},
            {x:494,y:530,w:16,h:17},
            {x:513,y:530,w:16,h:17},
            {x:532,y:530,w:16,h:17},
            {x:552,y:530,w:16,h:17},
        ],
        piety:[
            {x: 87,y:559,w:16,h:17},
            {x:107,y:559,w:16,h:17},
            {x:126,y:559,w:16,h:17},
            {x:145,y:559,w:16,h:17},
            {x:164,y:559,w:16,h:17},
            {x:184,y:559,w:16,h:17},
            {x:203,y:559,w:16,h:17},
            {x:222,y:559,w:16,h:17},
            {x:242,y:559,w:16,h:17},
            {x:261,y:559,w:16,h:17},
            {x:281,y:559,w:16,h:17},
            {x:300,y:559,w:16,h:17},
            {x:320,y:559,w:16,h:17},
            {x:339,y:559,w:16,h:17},
            {x:358,y:559,w:16,h:17},
            {x:378,y:559,w:16,h:17},
            {x:397,y:559,w:16,h:17},
            {x:416,y:559,w:16,h:17},
            {x:436,y:559,w:16,h:17},
            {x:455,y:559,w:16,h:17},
            {x:474,y:559,w:16,h:17},
            {x:494,y:559,w:16,h:17},
            {x:513,y:559,w:16,h:17},
            {x:532,y:559,w:16,h:17},
            {x:552,y:559,w:16,h:17},
        ],
        valour:[
            {x: 87,y:586,w:16,h:17},
            {x:107,y:586,w:16,h:17},
            {x:126,y:586,w:16,h:17},
            {x:145,y:586,w:16,h:17},
            {x:164,y:586,w:16,h:17},
            {x:184,y:586,w:16,h:17},
            {x:203,y:586,w:16,h:17},
            {x:222,y:586,w:16,h:17},
            {x:242,y:586,w:16,h:17},
            {x:261,y:586,w:16,h:17},
            {x:281,y:586,w:16,h:17},
            {x:300,y:586,w:16,h:17},
            {x:320,y:586,w:16,h:17},
            {x:339,y:586,w:16,h:17},
            {x:358,y:586,w:16,h:17},
            {x:378,y:586,w:16,h:17},
            {x:397,y:586,w:16,h:17},
            {x:416,y:586,w:16,h:17},
            {x:436,y:586,w:16,h:17},
            {x:455,y:586,w:16,h:17},
            {x:474,y:586,w:16,h:17},
            {x:494,y:586,w:16,h:17},
            {x:513,y:586,w:16,h:17},
            {x:532,y:586,w:16,h:17},
            {x:552,y:586,w:16,h:17},
        ],
        discipline:[
            {x: 87,y:615,w:16,h:17},
            {x:107,y:615,w:16,h:17},
            {x:126,y:615,w:16,h:17},
            {x:145,y:615,w:16,h:17},
            {x:164,y:615,w:16,h:17},
            {x:184,y:615,w:16,h:17},
            {x:203,y:615,w:16,h:17},
            {x:222,y:615,w:16,h:17},
            {x:242,y:615,w:16,h:17},
            {x:261,y:615,w:16,h:17},
            {x:281,y:615,w:16,h:17},
            {x:300,y:615,w:16,h:17},
            {x:320,y:615,w:16,h:17},
            {x:339,y:615,w:16,h:17},
            {x:358,y:615,w:16,h:17},
            {x:378,y:615,w:16,h:17},
            {x:397,y:615,w:16,h:17},
            {x:416,y:615,w:16,h:17},
            {x:436,y:615,w:16,h:17},
            {x:455,y:615,w:16,h:17},
            {x:474,y:615,w:16,h:17},
            {x:494,y:615,w:16,h:17},
            {x:513,y:615,w:16,h:17},
            {x:532,y:615,w:16,h:17},
            {x:552,y:615,w:16,h:17},
        ],
        disdain:[
            {x:202,y:677,w:10,h:10,class:'circle'},
            {x:217,y:677,w:10,h:10,class:'circle'},
            {x:233,y:677,w:10,h:10,class:'circle'},
            {x:249,y:677,w:10,h:10,class:'circle'},
            {x:265,y:677,w:10,h:10,class:'circle'},

            {x:202,y:691.5,w:10,h:10,class:'circle'},
            {x:217,y:691.5,w:10,h:10,class:'circle'},
            {x:233,y:691.5,w:10,h:10,class:'circle'},
            {x:249,y:691.5,w:10,h:10,class:'circle'},
            {x:265,y:691.5,w:10,h:10,class:'circle'},

            {x:202,y:706,w:10,h:10,class:'circle'},
            {x:217,y:706,w:10,h:10,class:'circle'},
            {x:233,y:706,w:10,h:10,class:'circle'},
            {x:249,y:706,w:10,h:10,class:'circle'},
            {x:265,y:706,w:10,h:10,class:'circle'},
        ],
        removed_disdain:[
            // {x:201,y:676,w:11,h:11,circle:true},
            // {x:216,y:676,w:11,h:11,circle:true},
            // {x:232,y:676,w:11,h:11,circle:true},
            // {x:248,y:676,w:11,h:11,circle:true},
            // {x:264,y:676,w:11,h:11,circle:true},

            // {x:201,y:691,w:11,h:11,circle:true},
            // {x:216,y:691,w:11,h:11,circle:true},
            // {x:232,y:691,w:11,h:11,circle:true},
            // {x:248,y:691,w:11,h:11,circle:true},
            // {x:264,y:691,w:11,h:11,circle:true},

            // {x:201,y:705,w:11,h:11,circle:true},
            // {x:216,y:705,w:11,h:11,circle:true},
            // {x:232,y:705,w:11,h:11,circle:true},
            // {x:248,y:705,w:11,h:11,circle:true},
            // {x:264,y:705,w:11,h:11,circle:true},
        ],
    },
    sheet2:{
        traders:[
            {x: 12,y:95,w:16,h:16},
            {x: 31,y:95,w:16,h:16},
            {x: 51,y:95,w:16,h:16},
            {x: 70,y:95,w:16,h:16},
            {x: 89,y:95,w:16,h:16},
            {x:109,y:95,w:16,h:16},
            {x:128,y:95,w:16,h:16},
            {x:147.5,y:95,w:16,h:16},
            {x:167,y:95,w:16,h:16},
        ],
        precinct:[
            {x:306,y:17,w:44,h:16,r:[PIETY,RESOURCE_PRODUCTION,RESOURCE],c:[SERVANT,CIVILIAN]},
            {x:306,y:41,w:44,h:16,r:[VALOUR,RESOURCE_PRODUCTION,RESOURCE],c:[SERVANT,CIVILIAN,CIVILIAN]},
            {x:306,y:63,w:44,h:16,r:[RENOWN,RESOURCE_PRODUCTION,RESOURCE],c:[SERVANT,CIVILIAN,CIVILIAN,CIVILIAN]},
        ],
        market:[
            {x:479,y:17,w:16,h:16,r:[RENOWN]},
        ],
        market_c1r1:[
            {x:422,y:40,w:20,h:18,class:'roundNumber',value:1},
        ],
        market_c1r2:[
            {x:422,y:63,w:20,h:18,class:'roundNumber',value:2},
        ],
        market_3:[
            {x:507,y:40,w:20,h:18,class:'roundNumber',value:3},
        ],
        market_4:[
            {x:507,y:63,w:20,h:18,class:'roundNumber',value:4},
        ],
        market_5:[
            {x:592,y:40,w:20,h:18,class:'roundNumber',value:5},
        ],
        market_6:[
            {x:592,y:63,w:20,h:18,class:'roundNumber',value:6},
        ],
        market_7:[
            {x:677,y:40,w:20,h:18,class:'roundNumber',value:7},
            {x:709,y:40,w:16,h:16},
        ],
        market_8:[
            {x:677,y:63,w:20,h:18,class:'roundNumber',value:8},
            {x:709,y:63,w:16,h:16},
        ],
        performers:[
            {x: 12,y:261,w:16,h:16},
            {x: 31,y:261,w:16,h:16},
            {x: 51,y:261,w:16,h:16},
            {x: 70,y:261,w:16,h:16},
            {x: 89,y:261,w:16,h:16},
            {x:109,y:261,w:16,h:16},
            {x:128,y:261,w:16,h:16},
            {x:147.5,y:261,w:16,h:16},
            {x:167,y:261,w:16,h:16},
        ],
        theatre:[
            {x:316,y:148,w:16,h:16,r:[RENOWN]},
        ],
        theatre_1:[
            {x:256,y:173,w:32,h:16,r:[RENOWN]},
            {x:290,y:173,w:18,h:18,class:'roundNumber',value:1,r:[RENOWN]},
        ],
        theatre_2:[
            {x:256,y:195,w:32,h:16,r:[RENOWN]},
            {x:290,y:195,w:18,h:18,class:'roundNumber',value:1,r:[RENOWN]},
        ],
        theatre_3:[
            {x:256,y:219,w:32,h:16,r:[RENOWN]},
            {x:290,y:219,w:18,h:18,class:'roundNumber',value:1,r:[RENOWN]},
        ],
        theatre_4:[
            {x:375,y:173,w:33,h:16,r:[RENOWN]},
            {x:410,y:173,w:18,h:18,class:'roundNumber',value:1,r:[RENOWN]},
        ],
        theatre_5:[
            {x:375,y:195,w:33,h:16,r:[RENOWN]},
            {x:410,y:195,w:18,h:18,class:'roundNumber',value:1,r:[RENOWN]},
        ],
        theatre_6:[
            {x:375,y:219,w:33,h:16,r:[RENOWN]},
            {x:410,y:219,w:18,h:18,class:'roundNumber',value:1,r:[RENOWN]},
        ],
        gladiatorious:[
            {x:618,y:148,w:16,h:16,r:[RENOWN]},
        ],
        red_training:[
            {x:479,y:195,w:19,h:19,class:'circle'},
            {x:512,y:195,w:19,h:19,class:'circle'},
            {x:546,y:195,w:19,h:19,class:'circle'},
            {x:580,y:195,w:19,h:19,class:'circle'},
            {x:613,y:195,w:19,h:19,class:'circle'},
            {x:646,y:195,w:19,h:19,class:'circle'},
        ],
        red_damage:[
            // {x:479,y:195,w:19,h:19,class:'circle'},
            // {x:512,y:195,w:19,h:19,class:'circle'},
            // {x:546,y:195,w:19,h:19,class:'circle'},
            // {x:580,y:195,w:19,h:19,class:'circle'},
            // {x:613,y:195,w:19,h:19,class:'circle'},
            // {x:646,y:195,w:19,h:19,class:'circle'},
        ],
        blue_training:[
            {x:479,y:219,w:19,h:19,class:'circle'},
            {x:512,y:219,w:19,h:19,class:'circle'},
            {x:546,y:219,w:19,h:19,class:'circle'},
            {x:580,y:219,w:19,h:19,class:'circle'},
            {x:613,y:219,w:19,h:19,class:'circle'},
            {x:646,y:219,w:19,h:19,class:'circle'},
        ],
        blue_damage:[
            // {x:479,y:219,w:19,h:19,class:'circle'},
            // {x:512,y:219,w:19,h:19,class:'circle'},
            // {x:546,y:219,w:19,h:19,class:'circle'},
            // {x:580,y:219,w:19,h:19,class:'circle'},
            // {x:613,y:219,w:19,h:19,class:'circle'},
            // {x:646,y:219,w:19,h:19,class:'circle'},
        ],
        red_combat:[],
        red_combat_1:[
            {x:687,y:173,w:18,h:18,class:'roundNumber'},
        ],
        red_combat_2:[
            {x:687,y:193,w:18,h:18,class:'roundNumber'},
        ],
        red_combat_3:[
            {x:687,y:213,w:18,h:18,class:'roundNumber'},
        ],
        red_combat_4:[
            {x:687,y:233,w:18,h:18,class:'roundNumber'},
        ],
        red_combat_5:[
            {x:687,y:253,w:18,h:18,class:'roundNumber'},
        ],
        red_combat_6:[
            {x:687,y:273,w:18,h:18,class:'roundNumber'},
        ],
        blue_combat:[],
        blue_combat_1:[
            {x:707,y:173,w:18,h:18,class:'roundNumber'},
        ],
        blue_combat_2:[
            {x:707,y:193,w:18,h:18,class:'roundNumber'},
        ],
        blue_combat_3:[
            {x:707,y:213,w:18,h:18,class:'roundNumber'},
        ],
        blue_combat_4:[
            {x:707,y:233,w:18,h:18,class:'roundNumber'},
        ],
        blue_combat_5:[
            {x:707,y:253,w:18,h:18,class:'roundNumber'},
        ],
        blue_combat_6:[
            {x:707,y:273,w:18,h:18,class:'roundNumber'},
        ],
        priests:[
            {x: 12,y:406,w:16,h:16},
            {x: 31,y:406,w:16,h:16},
            {x: 51,y:406,w:16,h:16},
            {x: 70,y:406,w:16,h:16},
            {x: 89,y:406,w:16,h:16},
            {x:109,y:406,w:16,h:16},
            {x:128,y:406,w:16,h:16},
            {x:147.5,y:406,w:16,h:16},
            {x:167,y:406,w:16,h:16},
        ],
        gardens:[
            {x:424,y:317,w:66,h:16},
            {x:624,y:317,w:100,h:16},
        ],
        temple:[
            {x:416,y:352,w:16,h:16},
            {x:558,y:352,w:16,h:16},
            {x:708,y:352,w:16,h:16},
        ],
        small_temple:[
            {x:362,y:375.5,w:16,h:16},
            {x:382,y:375.5,w:26,h:16},
            {x:410,y:376,w:16,h:16,class:'circle'},
            {x:410,y:376,w:16,h:16,class:'circle'},
        ],
        medium_temple:[
            {x:501,y:376,w:16,h:16},
            {x:521,y:376,w:16,h:16},
            {x:501,y:399.5,w:16,h:16},
            {x:521,y:399.5,w:16,h:16},

            {x:501,y:422.5,w:16,h:16},
            {x:521,y:422.5,w:26,h:16},
            {x:549,y:422,w:16,h:16,class:'circle'},
            {x:549,y:422,w:16,h:16,class:'circle'},
        ],
        large_temple:[
            {x:643,y:376,w:16,h:16},
            {x:663,y:376,w:16,h:16},
            {x:643,y:399.5,w:16,h:16},
            {x:663,y:399.5,w:16,h:16},

            {x:643,y:422.5,w:16,h:16},
            {x:663,y:422.5,w:26,h:16},
            {x:691,y:422,w:16,h:16,class:'circle'},
            {x:691,y:422,w:16,h:16,class:'circle'},
        ],
        apparitores:[
            {x: 12,y:536,w:16,h:16},
            {x: 31,y:536,w:16,h:16},
            {x: 51,y:536,w:16,h:16},
            {x: 70,y:536,w:16,h:16},
            {x: 89,y:536,w:16,h:16},
            {x:109,y:536,w:16,h:16},
            {x:128,y:536,w:16,h:16},
            {x:147.5,y:536,w:16,h:16},
            {x:167,y:536,w:16,h:16},
        ],
        baths:[
            {x:315,y:459,w:16,h:17},
        ],
        baths_1:[
            {x:272,y:484,w:16,h:17},
            {x:291,y:485,w:17,h:18,class:'roundNumber',value:6},
        ],
        baths_2:[
            {x:272,y:507,w:16,h:17},
            {x:291,y:508,w:17,h:18,class:'roundNumber',value:6},
        ],
        baths_3:[
            {x:272,y:530,w:16,h:17},
            {x:291,y:531,w:17,h:18,class:'roundNumber',value:6},
        ],
        baths_4:[
            {x:392,y:483,w:16,h:17},
            {x:411,y:484,w:17,h:18,class:'roundNumber',value:6},
        ],
        baths_5:[
            {x:392,y:506,w:16,h:17},
            {x:411,y:507,w:17,h:18,class:'roundNumber',value:6},
        ],
        baths_6:[
            {x:392,y:529,w:16,h:17},
            {x:411,y:530,w:17,h:18,class:'roundNumber',value:6},
        ],
        courthouse:[
            {x:588,y:459,w:16,h:17},
        ],
        courthouse_c1_1:[
            {x:486,y:484,w:16,h:17},
            {x:505,y:485,w:17,h:18,class:'roundNumber',value:6},
        ],
        courthouse_c1_2:[
            {x:486,y:507,w:16,h:17},
            {x:505,y:508,w:17,h:18,class:'roundNumber',value:6},
        ],
        courthouse_c1_3:[
            {x:486,y:530,w:16,h:17},
            {x:505,y:531,w:17,h:18,class:'roundNumber',value:6},
        ],
        courthouse_c2_1:[
            {x:587,y:484,w:18,h:17},
            {x:608,y:485,w:17,h:18,class:'roundNumber',value:6},
        ],
        courthouse_c2_2:[
            {x:587,y:507,w:18,h:17},
            {x:608,y:508,w:17,h:18,class:'roundNumber',value:6},
        ],
        courthouse_c2_3:[
            {x:587,y:530,w:18,h:17},
            {x:608,y:531,w:17,h:18,class:'roundNumber',value:6},
        ],
        courthouse_c3_1:[
            {x:690,y:484,w:16,h:17},
            {x:709,y:485,w:17,h:18,class:'roundNumber',value:6},
        ],
        courthouse_c3_2:[
            {x:690,y:507,w:16,h:17},
            {x:709,y:508,w:17,h:18,class:'roundNumber',value:6},
        ],
        courthouse_c3_3:[
            {x:690,y:530,w:16,h:17},
            {x:709,y:531,w:17,h:18,class:'roundNumber',value:6},
        ],
        patricians:[
            {x: 12,y:689,w:16,h:16},
            {x: 31,y:689,w:16,h:16},
            {x: 51,y:689,w:16,h:16},
            {x: 70,y:689,w:16,h:16},
            {x: 89,y:689,w:16,h:16},
            {x:109,y:689,w:16,h:16},
            {x:128,y:689,w:16,h:16},
            {x:147.5,y:689,w:16,h:16},
            {x:167,y:689,w:16,h:16},
        ],
        diplomat:[
            {x:318,y:594,w:18,h:16},
            {x:318+28,y:593.5,w:16,h:16,class:'circle'},
            {x:318+47,y:593.5,w:16,h:16,class:'circle'},

            {x:318,y:594+22,w:18,h:16},
            {x:318+28,y:594+22,w:16,h:16,class:'circle'},
            {x:318+47,y:594+22,w:16,h:16,class:'circle'},

            {x:318,y:594+45,w:18,h:16},
            {x:318+28,y:594+45,w:16,h:16,class:'circle'},
            {x:318+47,y:594+45,w:16,h:16,class:'circle'},
        ],
        diplomat_1_direction:[

        ],
        diplomat_1_favor:[

        ],
        diplomat_2_direction:[
        ],
        diplomat_2_favor:[

        ],
        diplomat_3_direction:[

        ],
        diplomat_3_favor:[

        ],
        scout:[
            {x:551,y:594,w:16,h:16},
            {x:551,y:594+22,w:16,h:16},
            {x:551,y:594+45,w:16,h:16},
            {x:551,y:594+68,w:16,h:16},
            {x:551,y:594+92,w:16,h:16},
        ],
        map:[
            {x:608,y:600,w:16,h:16},
            {x:608,y:620,w:16,h:16},
            {x:608,y:640,w:16,h:16},
            {x:608,y:660,w:16,h:16},

            {x:628,y:600,w:16,h:16},
            {x:628,y:620,w:16,h:16},
            {x:628,y:640,w:16,h:16},
            {x:628,y:660,w:16,h:16},

            {x:648,y:600,w:16,h:16},
            {x:648,y:620,w:16,h:16},
            {x:648,y:640,w:16,h:16},
            {x:648,y:660,w:16,h:16},

            {x:668,y:600,w:16,h:16},
            {x:668,y:620,w:16,h:16},
            {x:668,y:640,w:16,h:16},
            {x:668,y:660,w:16,h:16},

            {x:688,y:600,w:16,h:16},
            {x:688,y:620,w:16,h:16},
            {x:688,y:640,w:16,h:16},
            {x:688,y:660,w:16,h:16},

        ]
    }
}

*/