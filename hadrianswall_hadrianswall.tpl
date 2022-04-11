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
    <div id="sheet2">
        <div id="trade_options">
            <div id="trade_left" class="iconsheet icon_trade_good">#</div>
            <div id="trade_fate" class="iconsheet icon_trade_good">?</div>
            <div id="trade_right" class="iconsheet icon_trade_good">#</div>
        </div>
        <div id="scout_options">
            <div id="scout_left" class="iconsheet icon_scout"></div>
            <div id="scout_fate" class="iconsheet icon_scout"></div>
            <div id="scout_right" class="iconsheet icon_scout"></div>
        </div>
    </div>

    <div id="hand" class="forcehidden">
        <div id="card_choice_1" class="playercardsheet player_card_9 card_in_hand"></div>
        <div id="card_choice_2" class="playercardsheet player_card_10 card_in_hand"></div>
    </div>

    <div id="fate" class="forcehidden">
        <div id="fate_card" class="pictcardsheet pict_card_9 card_in_hand"></div>
    </div>

    <div id="production" class="forcehidden">
        <div class="iconsheet icon_brick"></div>
        <div class="iconsheet icon_brick"></div>
        <div class="iconsheet icon_brick"></div>
        <div class="iconsheet icon_brick"></div>
        <div class="iconsheet icon_brick"></div>
        <div class="iconsheet icon_brick"></div>
        <div class="iconsheet icon_brick"></div>
        <div class="iconsheet icon_brick"></div>
        <div class="iconsheet icon_brick"></div>
        <div class="iconsheet icon_civilian"></div>
        <div class="iconsheet icon_civilian"></div>
        <div class="iconsheet icon_builder"></div>
        <div class="iconsheet icon_builder"></div>
    </div>

</div>

<div id="pboard_space" class="pboard_space">
    <!-- BEGIN player_board -->
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
                <div style="width: 76px;">
                <div id="goal1_{COLOR}" class="playercardsheet player_back_{COLOR} card_banner_only "></div>
                <div id="goal4_{COLOR}" class="playercardsheet player_back_{COLOR} card_banner_only "></div>
                </div>
                <div style="width: 76px;">
                <div id="goal2_{COLOR}" class="playercardsheet player_back_{COLOR} card_banner_only "></div>
                <div id="goal5_{COLOR}" class="playercardsheet player_back_{COLOR} card_banner_only "></div>
                </div>
                <div style="width: 76px;">
                <div id="goal3_{COLOR}" class="playercardsheet player_back_{COLOR} card_banner_only "></div>
                <div id="goal6_{COLOR}" class="playercardsheet player_back_{COLOR} card_banner_only "></div>
                </div>
            </div>

        </div>
    </div>
    <!-- END player_board -->

    <!-- BEGIN my_player_board -->
    <div id="miniboard_{COLOR}" class="resourceboard">
        <div id="soldier_resource" class="attribute_backing">
            <div class="iconsheet icon_soldier miniicon"></div>
            <div id="soldiers_resource" class="attribute_score"></div>
        </div>
        <div id="builder_resource" class="attribute_backing">
            <div class="iconsheet icon_builder miniicon"></div>
            <div id="builders_resource" class="attribute_score"></div>
        </div>
        <div id="servant_resource" class="attribute_backing">
            <div class="iconsheet icon_servant miniicon"></div>
            <div id="servants_resource" class="attribute_score"></div>
        </div>
        <div id="civilian_resource" class="attribute_backing">
            <div class="iconsheet icon_civilian miniicon"></div>
            <div id="civilians_resource" class="attribute_score"></div>
        </div>
        <div id="brick_resource" class="attribute_backing">
            <div class="iconsheet icon_brick miniicon"></div>
            <div id="bricks_resource" class="attribute_score"></div>
        </div>
        <div></div>
        <div id="path_cards_attribute" class="attribute_backing" style="display: flex; flex-direction: row;">
            <div style="width: 114px;">
            <div id="goal1_{COLOR}" class="playercardsheet player_back_{COLOR} card_top_only "></div>
            <div id="goal3_{COLOR}" class="playercardsheet player_back_{COLOR} card_top_only "></div>
            <div id="goal5_{COLOR}" class="playercardsheet player_back_{COLOR} card_top_only "></div>
            </div>
            <div style="width: 114px;">
            <div id="goal2_{COLOR}" class="playercardsheet player_back_{COLOR} card_top_only "></div>
            <div id="goal4_{COLOR}" class="playercardsheet player_back_{COLOR} card_top_only "></div>
            <div id="goal6_{COLOR}" class="playercardsheet player_back_{COLOR} card_top_only "></div>
            </div>
        </div>
    </div>
    <!-- END my_player_board -->

</div>


<div id="playerboard1"  class="forcehidden">
    <div id="round_1_goal" class="playercardsheet player_card_1 goal goal_round_1 hidden"></div>
    <div id="round_2_goal" class="playercardsheet player_card_7 goal goal_round_2 hidden"></div>
    <div id="round_3_goal" class="playercardsheet player_card_3 goal goal_round_3 hidden"></div>
</div>

<div id="playerboard2" class="forcehidden">
    <div id="round_4_goal" class="playercardsheet player_card_4 goal goal_round_4 hidden"></div>
    <div id="round_5_goal" class="playercardsheet player_card_5 goal goal_round_5 hidden"></div>
    <div id="round_6_goal" class="playercardsheet player_card_6 goal goal_round_6 hidden"></div>
</div>


<script type="text/javascript">

// Javascript HTML templates
var jstpl_scratch='<div id="${id}" class="clickable outlined ${class}" style="position:absolute;left:${x}px;top:${y}px;width:${w}px;height:${h}px;">${value}</div>';


</script>  

{OVERALL_GAME_FOOTER}
