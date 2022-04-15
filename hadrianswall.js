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

            let goals={};
            let goals_map = gamedatas.goals;
            let goal_objs = Object.values(goals_map[0]);
            goal_objs.forEach((g)=>{
                goals[g.id] = g;
            })
            gamedatas.goals = goals;

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
            let resources = gamedatas.resources[0];
            // add resource counters
            [`civilians`,`servants`,`soldiers`,`builders`,`bricks`].forEach(resource=>{
                let counter = new ebg.counter();
                let dom_id = `${resource}_resource`
                counter.create(dom_id);
                counter.setValue(parseInt(resources[resource]));
                this[`${resource}_resource`] = counter;
            });
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
            
            if(gamedatas.board[player_id]){
                let board = gamedatas.board[player_id];
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
            
            [1,2,3,4,5,6].forEach((i)=>{
                if(gamedatas.goals[player_id][`round_${i}`]>0) {
                    let card_num = gamedatas.goals[player_id][`round_${i}`];
                    let node = dojo.query(`#goal${i}_${player.color}`);
                    node.addClass(`player_card_${card_num}`);  
                }
            })
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

                case 'acceptProducedResources':
                    dojo.removeClass('production','forcehidden');
                break;

                case 'chooseGeneratedAttributes':
                    let options = args
                    if(options.length===0) {
                        debug("options.length",options.length)
                        this.ajaxcall("/hadrianswall/hadrianswall/chooseAttribute.html",
                        {attribute:'none'},this,function(result){});        
                    }
                break;

                case 'chooseGoalCard':
                    dojo.removeClass('hand','forcehidden');
                break;

                case 'useResources':
                    //dojo.addClass('hand','forcehidden');
                break;

                case 'acceptPictAttack':
                    dojo.removeClass('attack','forcehidden');
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
                    dojo.addClass('production','forcehidden');
                break;

                case 'chooseGoalCard':
                    dojo.addClass('hand','forcehidden');
                break;           
           
                case 'acceptPictAttack':
                    dojo.addClass('attack','forcehidden');
                break;

            case 'dummmy':
                break;
            }               
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
                    case 'acceptFateResources':
                        this.addActionButton( 'acceptFate', _('Accept Workers and Resources'), 'actFateResources' );
                    break;

                    case 'acceptProducedResources':
                        this.addActionButton( 'acceptProducedResources', _('Accept produced Workers and Resources'), 'actProducedResources' );
                    break;

                    case 'chooseGeneratedAttributes': {

                        let options = args
                        options.forEach((option)=>{
                            console.log(`Option ${option}`)
                            this.addActionButton( `$[option}`,`<div id='${option}' class='iconsheet icon_${option} miniicon'></div>`,'actChooseAttribute');
                        })

                        // this.addActionButton( 'renown',"<div id='renown' class='iconsheet icon_renown miniicon'></div>",'actChooseAttribute');
                        // this.addActionButton( 'piety',"<div id='piety' class='iconsheet icon_piety miniicon'></div>",'actChooseAttribute');
                        // this.addActionButton( 'valour',"<div id='valour' class='iconsheet icon_valour miniicon'></div>",'actChooseAttribute');
                        // this.addActionButton( 'discipline',"<div id='discipline' class='iconsheet icon_discipline miniicon'></div>",'actChooseAttribute');

                    }
                    break;
                    
                    case 'chooseGoalCard':
                        this.addActionButton( 'hand_card_1', _('Left Card'), 'actHandCardChosen' );
                        this.addActionButton( 'hand_card_2', _('Right Card'), 'actHandCardChosen' );
                    break;

                    case 'useResources':
                        this.addActionButton( 'undo', _('Undo'), 'actTurnUndo' );
                        this.addActionButton( 'reset', _('Reset Turn'), 'actTurnReset' );
                        this.addActionButton( 'done', _('End Turn'), 'actTurnDone' );
                    break;

                    case 'gainValourAndDisdain':
                        this.addActionButton( 'accept', _('Accept'), 'actAttackResults');
                    break;


                }
            }
        },        

        ///////////////////////////////////////////////////
        //// Utility methods
        addScratchLocations: function() {
            console.log("Adding scratch loctions");
            Object.keys(scratch_data).forEach((sheet)=>{
                Object.keys(scratch_data[sheet]).forEach((zone)=>{
                    scratch_data[sheet][zone].forEach((cord,i)=>{
                        let id = `${zone}__s${i+1}`;
                        let scratch = this.format_block('jstpl_scratch',{id: id, class: "", value: "", ...cord});
                        dojo.place(scratch, sheet);
                    })
                })
            });
        },

        drawScratch: function (zone, value) {
            let id = `${zone}__s${value}`;
            if(dojo.query(`#${id}`).length==0){
                console.log(`Didn't find node for ${id}`);
                return;
            }
            dojo.removeClass(id,'outlined');
            dojo.addClass(id,'scratch');
        },

        drawScratches: function (zone, value) {
            if(value>0) {
                console.log(`Scratching ${zone} to ${value}`);
                for(var i=1; i<=value; i++){
                    this.drawScratch(zone,i);
                }
            }
        },

        drawAllScratches: function (board) {
            Object.keys(board).forEach(key=>{
                if(key==="player_id" || key==="round") {
                    return;
                }
                this.drawScratches(key,board[key]);
            })
        },

        ///////////////////////////////////////////////////
        //// Player's action
        onBoxClicked: function( evt )
        {
            dojo.stopEvent(evt);

            let section = evt.target.id.split("__")[0];
            debug("Box clicked",section);

            if(this.checkAction('checkNextBox')){
                console.log("ajax call with ",section);
                this.ajaxcall("/hadrianswall/hadrianswall/checkNextBox.html",{section},this,function(result){});
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

            if(this.checkAction('chooseCard')){
                this.ajaxcall("/hadrianswall/hadrianswall/chooseCard.html",
                    {
                        card_id
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

            if(this.checkAction('endTurn')){
                this.ajaxcall("/hadrianswall/hadrianswall/endTurn.html",
                    {},this,function(result){});
            }
        },

        actAttackResults: function( evt ) {
            dojo.stopEvent( evt );
            debug("Accept attack results",evt)

            if(this.checkAction('acceptAttackResults')){
                this.ajaxcall("/hadrianswall/hadrianswall/acceptAttackResults.html",
                    {},this,function(result){});
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
            
        },  

        notif_newRound: function(notif) {
            debug('notif_newRound',notif);
            debug('args',notif.args);
        },

        notif_sheetsUpdated: function(notif) {
            debug('notif_newAvailableFields',notif);
            let board = notif.args.board;
            this.drawAllScratches(board);
        },

        notif_resourcesUpdated: function(notif) {
            debug('notif_resourcesUpdated',notif);
            let resources = notif.args.resources;
            debug('resources',resources);

            [`civilians`,`servants`,`soldiers`,`builders`,`bricks`].forEach(resource=>{
                if(resources[resource]) {
                    this[`${resource}_resource`].setValue( resources[resource] );
                }
            });


        }
   });             
});





const PLUS = 'PLUS';
const RESOURCE = 'RESOURCE';
const RESOURCE_PRODUCTION = 'RESOURCE_PRODUCTION';
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


let scratch_data = {
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
            {x:137,y:75,w:36,h:17,r:[RESOURCE_PRODUCTION,RESOURCE]},
            {x:206,y:75,w:16,h:17},
            {x:254,y:75,w:16,h:17},
            {x:300,y:75,w:36,h:17,r:[RESOURCE_PRODUCTION,RESOURCE]},
            {x:356,y:75,w:16,h:17},
            {x:392,y:75,w:16,h:17},
            {x:428,y:75,w:36,h:17,r:[RESOURCE_PRODUCTION,RESOURCE]},
            {x:484,y:75,w:16,h:17},
            {x:520,y:75,w:16,h:17},
            {x:557,y:75,w:36,h:17,r:[RESOURCE_PRODUCTION,RESOURCE]},
            {x:613,y:75,w:16,h:17},
            {x:649,y:75,w:16,h:17},
            {x:685,y:75,w:36,h:17,r:[RESOURCE_PRODUCTION,RESOURCE]},
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
        training_grounds_1:[
            {x:463,y:355,w:13,h:16,class:'roundNumber',value:1},
        ],
        training_grounds_2:[
            {x:498,y:355,w:13,h:16,class:'roundNumber',value:2},
        ],
        training_grounds_3:[
            {x:533,y:355,w:13,h:16,class:'roundNumber',value:3},
        ],
        training_grounds_4:[
            {x:569,y:355,w:13,h:16,class:'roundNumber',value:4},
        ],
        training_grounds_5:[
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
        market_1:[
            {x:422,y:40,w:20,h:18,class:'roundNumber',value:1},
        ],
        market_2:[
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
