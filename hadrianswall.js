/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * HadriansWall implementation : © <Your name here> <Your email address here>
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

define([
    "dojo","dojo/_base/declare",
    "ebg/core/gamegui",
    "ebg/counter"
],
function (dojo, declare) {
    return declare("bgagame.hadrianswall", ebg.core.gamegui, {
        constructor: function(){
            console.log('hadrianswall constructor');
              
            // Here, you can init the global variables of your user interface
            // Example:
            // this.myGlobalValue = 0;

        },
        
        /*
            setup:
            
            This method must set up the game user interface according to current game situation specified
            in parameters.
            
            The method is called each time the game interface is displayed to a player, ie:
            _ when the game starts
            _ when a player refreshes the game page (F5)
            
            "gamedatas" argument contains all datas retrieved by your "getAllDatas" PHP method.
        */
        
        setup: function( gamedatas )
        {
            console.log( "Starting game setup" );
            console.log(gamedatas);

            this.addScratchLocations();
            
            // Setting up player boards
            for( var player_id in gamedatas.players )
            {
                var player = gamedatas.players[player_id];
                         
                // TODO: Setting up players boards if needed
                if(gamedatas.board[player_id]){
                    let board = gamedatas.board[player_id];
                    console.log(JSON.stringify(board));

                    Object.keys(board).forEach(key=>{
                        if(key==="player_id" || key==="round") {
                            return;
                        }
                        this.drawScratches(key,board[key]);
                    })
                }
            }
            
            // TODO: Set up your game interface here, according to "gamedatas"
 
            // Setup game notifications to handle (see "setupNotifications" method below)
            this.setupNotifications();

            console.log( "Ending game setup" );
        },
       

        ///////////////////////////////////////////////////
        //// Game & client states
        
        // onEnteringState: this method is called each time we are entering into a new game state.
        //                  You can use this method to perform some user interface changes at this moment.
        //
        onEnteringState: function( stateName, args )
        {
            console.log( 'Entering state: '+stateName );
            
            switch( stateName )
            {
            
            /* Example:
            
            case 'myGameState':
            
                // Show some HTML block at this game state
                dojo.style( 'my_html_block_id', 'display', 'block' );
                
                break;
           */
           
           
            case 'dummmy':
                break;
            }
        },

        // onLeavingState: this method is called each time we are leaving a game state.
        //                 You can use this method to perform some user interface changes at this moment.
        //
        onLeavingState: function( stateName )
        {
            console.log( 'Leaving state: '+stateName );
            
            switch( stateName )
            {
            
            /* Example:
            
            case 'myGameState':
            
                // Hide the HTML block we are displaying only during this game state
                dojo.style( 'my_html_block_id', 'display', 'none' );
                
                break;
           */
           
           
            case 'dummmy':
                break;
            }               
        }, 

        // onUpdateActionButtons: in this method you can manage "action buttons" that are displayed in the
        //                        action status bar (ie: the HTML links in the status bar).
        //        
        onUpdateActionButtons: function( stateName, args )
        {
            console.log( 'onUpdateActionButtons: '+stateName );
                      
            if( this.isCurrentPlayerActive() )
            {            
                switch( stateName )
                {
/*               
                 Example:
 
                 case 'myGameState':
                    
                    // Add 3 action buttons in the action status bar:
                    
                    this.addActionButton( 'button_1_id', _('Button 1 label'), 'onMyMethodToCall1' ); 
                    this.addActionButton( 'button_2_id', _('Button 2 label'), 'onMyMethodToCall2' ); 
                    this.addActionButton( 'button_3_id', _('Button 3 label'), 'onMyMethodToCall3' ); 
                    break;
*/
                }
            }
        },        

        ///////////////////////////////////////////////////
        //// Utility methods
        
        /*
        
            Here, you can defines some utility methods that you can use everywhere in your javascript
            script.
        
        */

        addScratchLocations: function() {
            console.log("Adding scratch loctions");
            Object.keys(sheets_scratch_locations).forEach((sheet)=>{
                Object.keys(sheets_scratch_locations[sheet]).forEach((zone)=>{
                    sheets_scratch_locations[sheet][zone].forEach((cord,i)=>{
                        console.log(`Sheet: ${sheet}  Zone: ${zone}  Cord: ${cord}  for index ${i}`);

                        let id = `${zone}_s${i+1}`;
                        let scratch = this.format_block('jstpl_scratch',{id: id, ...cord, radius:(cord.circle?cord.w/2:0)});
                        console.log(`Adding: ${scratch}`);
                        dojo.place(scratch, sheet);
                    })
                })
            });
        },

        drawScratch: function (zone, value) {
            let id = `${zone}_s${value}`;
            if(dojo.query(`#${id}`).length==0){
                console.log(`Didn't find node for ${id}`);
                return;
            }

            console.log(`Scratching ${zone} box ${value}`);
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

        ///////////////////////////////////////////////////
        //// Player's action
        
        /*
        
            Here, you are defining methods to handle player's action (ex: results of mouse click on 
            game objects).
            
            Most of the time, these methods:
            _ check the action is possible at this game state.
            _ make a call to the game server
        
        */
        
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
        
        */
        setupNotifications: function()
        {
            console.log( 'notifications subscriptions setup' );
            
            // TODO: here, associate your game notifications with local methods
            
            // Example 1: standard notification handling
            // dojo.subscribe( 'cardPlayed', this, "notif_cardPlayed" );
            
            // Example 2: standard notification handling + tell the user interface to wait
            //            during 3 seconds after calling the method in order to let the players
            //            see what is happening in the game.
            // dojo.subscribe( 'cardPlayed', this, "notif_cardPlayed" );
            // this.notifqueue.setSynchronous( 'cardPlayed', 3000 );
            // 
        },  
        
        // TODO: from this point and below, you can write your game notifications handling methods
        
        /*
        Example:
        
        notif_cardPlayed: function( notif )
        {
            console.log( 'notif_cardPlayed' );
            console.log( notif );
            
            // Note: notif.args contains the arguments specified during you "notifyAllPlayers" / "notifyPlayer" PHP call
            
            // TODO: play the card in the user interface.
        },    
        
        */
   });             
});

let sheets_scratch_locations = {
    sheet1:{
        left_cohort:[
            {x: 128,y:24,w:16,h:17},
            {x: 148,y:24,w:16,h:17},
            {x: 168,y:24,w:16,h:17},
            {x: 187,y:24,w:16,h:17},
            {x: 206,y:24,w:16,h:17},
            {x: 225,y:24,w:16,h:17},
        ],
        center_cohort:[
            {x: 355,y:23,w:16,h:17},
            {x: 375,y:23,w:16,h:17},
            {x: 395,y:23,w:16,h:17},
            {x: 414,y:23,w:16,h:17},
            {x: 433,y:23,w:16,h:17},
            {x: 452,y:23,w:16,h:17},
        ],
        right_cohort:[
            {x: 582,y:21,w:16,h:17},
            {x: 602,y:21,w:16,h:17},
            {x: 621,y:21,w:16,h:17},
            {x: 641,y:21,w:16,h:17},
            {x: 660,y:21,w:16,h:17},
            {x: 679,y:21,w:16,h:17},
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
            {x: 81,y:122,w:16,h:17},            
            {x:117,y:122,w:16,h:17},            
            {x:153,y:122,w:16,h:17},            
            {x:190,y:122,w:16,h:17},            
            {x:225,y:122,w:16,h:17},            
            {x:262,y:122,w:16,h:17},            
            {x:302,y:122,w:16,h:17},            
            {x:338,y:122,w:16,h:17},            
            {x:373,y:122,w:16,h:17},            
            {x:410,y:122,w:16,h:17},            
            {x:446,y:122,w:16,h:17},            
            {x:482,y:122,w:16,h:17},            
            {x:521,y:122,w:16,h:17},            
            {x:558,y:122,w:16,h:17},            
            {x:594,y:122,w:16,h:17},            
            {x:631,y:122,w:16,h:17},            
            {x:666,y:122,w:16,h:17},            
            {x:702,y:122,w:16,h:17},            
        ],
        cippi:[
            {x: 94,y:171,w:16,h:17},
            {x:191,y:171,w:16,h:17},
            {x:268,y:171,w:16,h:17},
            {x:353,y:171,w:16,h:17},
            {x:450,y:171,w:16,h:17},
            {x:554,y:171,w:16,h:17},
            {x:671,y:171,w:16,h:17},
        ],
        wall:[
            {x: 74,y:219,w:16,h:17},
            {x:113,y:219,w:16,h:17},
            {x:133,y:219,w:16,h:17},
            {x:153,y:219,w:36,h:17},
            {x:210,y:219,w:16,h:17},
            {x:230,y:219,w:16,h:17},
            {x:250,y:219,w:16,h:17},
            {x:295,y:219,w:16,h:17},
            {x:315,y:219,w:16,h:17},
            {x:335,y:219,w:16,h:17},
            {x:372,y:219,w:36,h:17},
            {x:411,y:219,w:16,h:17},
            {x:431,y:219,w:16,h:17},
            {x:479,y:219,w:16,h:17},
            {x:515,y:219,w:16,h:17},
            {x:535,y:219,w:16,h:17},
            {x:573,y:219,w:16,h:17},
            {x:593,y:219,w:36,h:17},
            {x:632,y:219,w:16,h:17},
            {x:652,y:219,w:16,h:17},
            {x:700,y:219,w:16,h:17},
        ],
        fort:[
            {x: 75,y:266,w:36,h:18},
            {x:114,y:266,w:16,h:18},
            {x:133,y:266,w:16,h:18},
            {x:152,y:266,w:56,h:18},
            {x:210,y:266,w:16,h:18},
            {x:230,y:266,w:16,h:18},
            {x:250,y:266,w:36,h:18},

            {x:295,y:266,w:16,h:18},
            {x:314,y:266,w:16,h:18},
            {x:334,y:266,w:36,h:18},
            {x:372,y:266,w:36,h:18},
            {x:411,y:266,w:16,h:18},
            {x:430,y:266,w:36,h:18},
            {x:469,y:266,w:36,h:18},

            {x:515,y:266,w:16,h:18},
            {x:535,y:266,w:36,h:18},
            {x:573,y:266,w:16,h:18},
            {x:593,y:266,w:36,h:18},
            {x:631,y:266,w:16,h:18},
            {x:651,y:266,w:36,h:18},
            {x:689,y:266,w:36,h:18},
        ],
        resource_production:[
            {x:104,y:357,w:13,h:13,circle:true},
            {x:123,y:357,w:13,h:13,circle:true},
            {x:142,y:357,w:13,h:13,circle:true},
            {x:161,y:357,w:13,h:13,circle:true},
            {x:179,y:357,w:13,h:13,circle:true},
            {x:198,y:357,w:13,h:13,circle:true},
            {x:217,y:357,w:13,h:13,circle:true},
            {x:236,y:357,w:13,h:13,circle:true},
        ],
        training_grounds:[],
        training_grounds_1:[],
        training_grounds_2:[],
        training_grounds_3:[],
        training_grounds_4:[],
        training_grounds_5:[],
        hotel:[],
        workshop:[],
        road:[],
        forum:[],
        forum_1:[],
        forum_2:[],
        forum_3:[],
        forum_4:[],
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
            {x:201,y:676,w:11,h:11,circle:true},
            {x:216,y:676,w:11,h:11,circle:true},
            {x:232,y:676,w:11,h:11,circle:true},
            {x:248,y:676,w:11,h:11,circle:true},
            {x:264,y:676,w:11,h:11,circle:true},

            {x:201,y:691,w:11,h:11,circle:true},
            {x:216,y:691,w:11,h:11,circle:true},
            {x:232,y:691,w:11,h:11,circle:true},
            {x:248,y:691,w:11,h:11,circle:true},
            {x:264,y:691,w:11,h:11,circle:true},

            {x:201,y:705,w:11,h:11,circle:true},
            {x:216,y:705,w:11,h:11,circle:true},
            {x:232,y:705,w:11,h:11,circle:true},
            {x:248,y:705,w:11,h:11,circle:true},
            {x:264,y:705,w:11,h:11,circle:true},
        ],
        removed_disdain:[],
    },
    sheet2:{
    }
}
