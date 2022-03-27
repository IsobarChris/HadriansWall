{OVERALL_GAME_HEADER}

<!-- 
--------
-- BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
-- HadriansWall implementation : © <Your name here> <Your email address here>
-- 
-- This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
-- See http://en.boardgamearena.com/#!doc/Studio for more information.
-------

    hadrianswall_hadrianswall.tpl
    
    This is the HTML template of your game.
    
    Everything you are writing in this file will be displayed in the HTML page of your game user interface,
    in the "main game zone" of the screen.
    
    You can use in this template:
    _ variables, with the format {MY_VARIABLE_ELEMENT}.
    _ HTML block, with the BEGIN/END format
    
    See your "view" PHP file to check how to set variables and control blocks
    
    Please REMOVE this comment before publishing your game on BGA
-->

<div>

<div id="playerboard1" class="">
    <div id="round_1_goal" class="playercardsheet player_card_1 goal goal_round_1"></div>
    <div id="round_2_goal" class="playercardsheet player_card_7 goal goal_round_2"></div>
    <div id="round_3_goal" class="playercardsheet player_card_3 goal goal_round_3"></div>

    <div id="round_1_l_pick" class="playercardbottomsheet player_cardbottom_9  op_pick op_l_pick_round_1"></div>
    <div id="round_1_pick" class="playercardbottomsheet player_cardbottom_10 pick pick_round_1"></div>
    <div id="round_1_r_pick" class="playercardbottomsheet player_cardbottom_11 op_pick op_r_pick_round_1"></div>

    <div id="round_2_l_pick" class="playercardbottomsheet player_cardbottom_9  op_pick op_l_pick_round_2"></div>
    <div id="round_2_pick" class="playercardbottomsheet player_cardbottom_10 pick pick_round_2"></div>
    <div id="round_2_r_pick" class="playercardbottomsheet player_cardbottom_11 op_pick op_r_pick_round_2"></div>

    <div id="round_3_l_pick" class="playercardbottomsheet player_cardbottom_9  op_pick op_l_pick_round_3"></div>
    <div id="round_3_pick" class="playercardbottomsheet player_cardbottom_10 pick pick_round_3"></div>
    <div id="round_3_r_pick" class="playercardbottomsheet player_cardbottom_11 op_pick op_r_pick_round_3"></div>
</div>



<div id="playerboard2" class="hidden">
    <div id="round_4_goal" class="playercardsheet player_card_4 goal goal_round_4"></div>
    <div id="round_5_goal" class="playercardsheet player_card_5 goal goal_round_5"></div>
    <div id="round_6_goal" class="playercardsheet player_card_6 goal goal_round_6"></div>

    <div id="round_4_l_pick" class="playercardbottomsheet player_cardbottom_9  op_pick op_l_pick_round_4"></div>
    <div id="round_4_pick" class="playercardbottomsheet player_cardbottom_10 pick pick_round_4"></div>
    <div id="round_4_r_pick" class="playercardbottomsheet player_cardbottom_11 op_pick op_r_pick_round_4"></div>

    <div id="round_5_l_pick" class="playercardbottomsheet player_cardbottom_9  op_pick op_l_pick_round_5"></div>
    <div id="round_5_pick" class="playercardbottomsheet player_cardbottom_10 pick pick_round_5"></div>
    <div id="round_5_r_pick" class="playercardbottomsheet player_cardbottom_11 op_pick op_r_pick_round_5"></div>

    <div id="round_6_l_pick" class="playercardbottomsheet player_cardbottom_9  op_pick op_l_pick_round_6"></div>
    <div id="round_6_pick" class="playercardbottomsheet player_cardbottom_10 pick pick_round_6"></div>
    <div id="round_6_r_pick" class="playercardbottomsheet player_cardbottom_11 op_pick op_r_pick_round_6"></div>
</div>


<div id="board1"></div>
<div id="board2"></div>
</div>

<div> 
    <div id="x" class="playercardsheet player_card_1"></div>
    <div id="x" class="playercardsheet player_card_2"></div>
    <div id="x" class="playercardsheet player_card_3"></div>
    <div id="x" class="playercardsheet player_card_4"></div>
    <div id="x" class="playercardsheet player_card_5"></div>
    <div id="x" class="playercardsheet player_card_6"></div>
    <div id="x" class="playercardsheet player_card_7"></div>
    <div id="x" class="playercardsheet player_card_8"></div>
    <div id="x" class="playercardsheet player_card_9"></div>
    <div id="x" class="playercardsheet player_card_10"></div>
    <div id="x" class="playercardsheet player_card_11"></div>
    <div id="x" class="playercardsheet player_card_12"></div>
</div>

<div> 
    <div id="x" class="pictcardsheet pict_card_1"></div>
    <div id="x" class="pictcardsheet pict_card_2"></div>
    <div id="x" class="pictcardsheet pict_card_3"></div>
    <div id="x" class="pictcardsheet pict_card_4"></div>
    <div id="x" class="pictcardsheet pict_card_5"></div>
    <div id="x" class="pictcardsheet pict_card_6"></div>
    <div id="x" class="pictcardsheet pict_card_7"></div>
    <div id="x" class="pictcardsheet pict_card_8"></div>
    <div id="x" class="pictcardsheet pict_card_9"></div>
    <div id="x" class="pictcardsheet pict_card_10"></div>
    <div id="x" class="pictcardsheet pict_card_11"></div>
    <div id="x" class="pictcardsheet pict_card_12"></div>
    <div id="x" class="pictcardsheet pict_card_13"></div>
    <div id="x" class="pictcardsheet pict_card_14"></div>
    <div id="x" class="pictcardsheet pict_card_15"></div>
    <div id="x" class="pictcardsheet pict_card_16"></div>
    <div id="x" class="pictcardsheet pict_card_17"></div>
    <div id="x" class="pictcardsheet pict_card_18"></div>
    <div id="x" class="pictcardsheet pict_card_19"></div>
    <div id="x" class="pictcardsheet pict_card_20"></div>
    <div id="x" class="pictcardsheet pict_card_21"></div>
    <div id="x" class="pictcardsheet pict_card_22"></div>
    <div id="x" class="pictcardsheet pict_card_23"></div>
    <div id="x" class="pictcardsheet pict_card_24"></div>
    <div id="x" class="pictcardsheet pict_card_25"></div>
    <div id="x" class="pictcardsheet pict_card_26"></div>
    <div id="x" class="pictcardsheet pict_card_27"></div>
    <div id="x" class="pictcardsheet pict_card_28"></div>
    <div id="x" class="pictcardsheet pict_card_29"></div>
    <div id="x" class="pictcardsheet pict_card_30"></div>
    <div id="x" class="pictcardsheet pict_card_31"></div>
    <div id="x" class="pictcardsheet pict_card_32"></div>
    <div id="x" class="pictcardsheet pict_card_33"></div>
    <div id="x" class="pictcardsheet pict_card_34"></div>
    <div id="x" class="pictcardsheet pict_card_35"></div>
    <div id="x" class="pictcardsheet pict_card_36"></div>
    <div id="x" class="pictcardsheet pict_card_37"></div>
    <div id="x" class="pictcardsheet pict_card_38"></div>
    <div id="x" class="pictcardsheet pict_card_39"></div>
    <div id="x" class="pictcardsheet pict_card_30"></div>
    <div id="x" class="pictcardsheet pict_card_41"></div>
    <div id="x" class="pictcardsheet pict_card_42"></div>
    <div id="x" class="pictcardsheet pict_card_43"></div>
    <div id="x" class="pictcardsheet pict_card_44"></div>
    <div id="x" class="pictcardsheet pict_card_45"></div>
    <div id="x" class="pictcardsheet pict_card_46"></div>
    <div id="x" class="pictcardsheet pict_card_47"></div>
    <div id="x" class="pictcardsheet pict_card_48"></div>
</div>


<script type="text/javascript">


// Javascript HTML templates

/*
// Example:
var jstpl_some_game_item='<div class="my_game_item" id="my_game_item_${MY_ITEM_ID}"></div>';

*/

</script>  

{OVERALL_GAME_FOOTER}
