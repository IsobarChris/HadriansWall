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
    <div id="sheet1"></div>
    <div id="sheet2"></div>
</div>

<div id="pboard_space" class="pboard_space">
    <!-- BEGIN player_board -->
    <!--  boardblock  -->
    <div id="miniboard_{COLOR}" class="miniboard">
        <div id="scoresheet">
            <div style="width:1px; height:10px;"> </div>
            <div id="renown_attribute" class="attribute_backing">
                <div class="iconsheet icon_renown miniicon"></div>
                <div id="renown_score_{COLOR}" class="attribute_score"></div>
            </div>
            <div id="piety_attribute" class="attribute_backing">
                <div class="iconsheet icon_piety miniicon"></div>
                <div id="piety_score_{COLOR}"  class="attribute_score"></div>
            </div>
            <div id="valour_attribute" class="attribute_backing">
                <div class="iconsheet icon_valour miniicon"></div>
                <div id="valour_score_{COLOR}"  class="attribute_score"></div>
            </div>
            <div id="discipline_attribute" class="attribute_backing">
                <div class="iconsheet icon_discipline miniicon"></div>
                <div id="discipline_score_{COLOR}"  class="attribute_score"></div>
            </div>
            <div id="disdain_attribute" class="attribute_backing">
                <div class="iconsheet icon_thumb_down miniicon"></div>
                <div id="disdain_score_{COLOR}"  class="attribute_score"></div>
            </div>

            <div></div>
            <div id="path_cards_attribute" class="attribute_backing" style="display: flex; flex-direction: row;">
                <div style="width: 100px;">
                <div id="goal1_{COLOR}" class="playercardsheet player_back_{COLOR} card_banner_only "></div>
                <div id="goal3_{COLOR}" class="playercardsheet player_back_{COLOR} card_banner_only "></div>
                <div id="goal5_{COLOR}" class="playercardsheet player_back_{COLOR} card_banner_only "></div>
                </div>
                <div style="width: 100px;">
                <div id="goal2_{COLOR}" class="playercardsheet player_back_{COLOR} card_banner_only "></div>
                <div id="goal4_{COLOR}" class="playercardsheet player_back_{COLOR} card_banner_only "></div>
                <div id="goal6_{COLOR}" class="playercardsheet player_back_{COLOR} card_banner_only "></div>
                </div>
            </div>

        </div>
    </div>
    <!-- END player_board -->

    <!-- BEGIN my_player_board -->
    <!--  boardblock  -->
    <div id="miniboard_{COLOR}" class="resourceboard">
        
    </div>
    <!-- END my_player_board -->

</div>

<div class="icons" style="width: 500px; height: 500px; background-color: #ffffff55;">
    <div class="iconsheet icon_round"></div>
    <div class="iconsheet icon_flag"></div>
    <div class="iconsheet icon_soldier"></div>
    <div class="iconsheet icon_servant"></div>
    <div class="iconsheet icon_builder"></div>
    <div class="iconsheet icon_civilian"></div>
    <div class="iconsheet icon_brick"></div>
    <div class="iconsheet icon_renown"></div>

    <div class="iconsheet icon_banner_gray"></div>
    <div class="iconsheet icon_banner_green"></div>
    <div class="iconsheet icon_banner_yellow"></div>
    <div class="iconsheet icon_banner_red"></div>
    <div class="iconsheet icon_drape_gray"></div>
    <div class="iconsheet icon_drape_green"></div>
    <div class="iconsheet icon_drape_yellow"></div>
    <div class="iconsheet icon_drape_red"></div>

    <div class="iconsheet icon_valour"></div>
    <div class="iconsheet icon_piety"></div>
    <div class="iconsheet icon_discipline"></div>
    <div class="iconsheet icon_trader"></div>
    <div class="iconsheet icon_performer"></div>
    <div class="iconsheet icon_priest"></div>
    <div class="iconsheet icon_apparitor"></div>
    <div class="iconsheet icon_patrician"></div>

    <div class="iconsheet icon_favor"></div>
    <div class="iconsheet icon_thumb_down"></div>
    <div class="iconsheet icon_thumb_up"></div>
    <div class="iconsheet icon_cohort"></div>
    <div class="iconsheet icon_sword"></div>
    <div class="iconsheet icon_production"></div>
    <div class="iconsheet icon_scout"></div>
    <div class="iconsheet icon_trade_good"></div>

    <div class="iconsheet icon_check_x"></div>
    <div class="iconsheet icon_check_mark"></div>
    <div class="iconsheet icon_glad_purple"></div>
    <div class="iconsheet icon_glad_blue"></div>
    <div class="iconsheet icon_glad_red"></div>
    <div class="iconsheet icon_diff_left"></div>
    <div class="iconsheet icon_diff_right"></div>
    <div class="iconsheet icon_blank"></div>
</div>    

<div class="hidden"> 
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

<div class="hidden"> 
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

<div id="playerboard1" class="veryhidden">
    <div id="round_1_goal" class="playercardsheet player_card_1 goal goal_round_1 hidden"></div>
    <div id="round_2_goal" class="playercardsheet player_card_7 goal goal_round_2 hidden"></div>
    <div id="round_3_goal" class="playercardsheet player_card_3 goal goal_round_3 hidden"></div>

    <div id="round_1_l_pick" class="playercardbottomsheet player_cardbottom_9  op_pick op_l_pick_round_1 hidden"></div>
    <div id="round_1_pick" class="playercardbottomsheet player_cardbottom_10 pick pick_round_1 hidden"></div>
    <div id="round_1_r_pick" class="playercardbottomsheet player_cardbottom_11 op_pick op_r_pick_round_1 hidden"></div>

    <div id="round_2_l_pick" class="playercardbottomsheet player_cardbottom_9  op_pick op_l_pick_round_2 hidden"></div>
    <div id="round_2_pick" class="playercardbottomsheet player_cardbottom_10 pick pick_round_2 hidden"></div>
    <div id="round_2_r_pick" class="playercardbottomsheet player_cardbottom_11 op_pick op_r_pick_round_2 hidden"></div>

    <div id="round_3_l_pick" class="playercardbottomsheet player_cardbottom_9  op_pick op_l_pick_round_3 hidden"></div>
    <div id="round_3_pick" class="playercardbottomsheet player_cardbottom_10 pick pick_round_3 hidden"></div>
    <div id="round_3_r_pick" class="playercardbottomsheet player_cardbottom_11 op_pick op_r_pick_round_3 hidden"></div>
</div>

<div id="playerboard2" class="veryhidden">
    <div id="round_4_goal" class="playercardsheet player_card_4 goal goal_round_4 hidden"></div>
    <div id="round_5_goal" class="playercardsheet player_card_5 goal goal_round_5 hidden"></div>
    <div id="round_6_goal" class="playercardsheet player_card_6 goal goal_round_6 hidden"></div>

    <div id="round_4_l_pick" class="playercardbottomsheet player_cardbottom_9  op_pick op_l_pick_round_4 hidden"></div>
    <div id="round_4_pick" class="playercardbottomsheet player_cardbottom_10 pick pick_round_4 hidden"></div>
    <div id="round_4_r_pick" class="playercardbottomsheet player_cardbottom_11 op_pick op_r_pick_round_4 hidden"></div>

    <div id="round_5_l_pick" class="playercardbottomsheet player_cardbottom_9  op_pick op_l_pick_round_5 hidden"></div>
    <div id="round_5_pick" class="playercardbottomsheet player_cardbottom_10 pick pick_round_5 hidden"></div>
    <div id="round_5_r_pick" class="playercardbottomsheet player_cardbottom_11 op_pick op_r_pick_round_5 hidden"></div>

    <div id="round_6_l_pick" class="playercardbottomsheet player_cardbottom_9  op_pick op_l_pick_round_6 hidden"></div>
    <div id="round_6_pick" class="playercardbottomsheet player_cardbottom_10 pick pick_round_6 hidden"></div>
    <div id="round_6_r_pick" class="playercardbottomsheet player_cardbottom_11 op_pick op_r_pick_round_6 hidden"></div>
</div>


<script type="text/javascript">

// Javascript HTML templates
/*
// Example:
var jstpl_some_game_item='<div class="my_game_item" id="my_game_item_${MY_ITEM_ID}"></div>';
*/

var jstpl_scratch='<div id="${id}" class="clickable outlined ${class}" style="position:absolute;left:${x}px;top:${y}px;width:${w}px;height:${h}px;">${value}</div>';

// <div id="checkmark" class="checkmark" style="left:90px;top:76px;"></div>


</script>  

{OVERALL_GAME_FOOTER}
