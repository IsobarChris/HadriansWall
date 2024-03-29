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
    <div id="sheet1">
        <div id="score_column">
            <div id="score_renown"     class="score_value">13</div>
            <div id="score_piety"      class="score_value">23</div>
            <div id="score_valour"     class="score_value">33</div>
            <div id="score_discipline" class="score_value">43</div>
            <div id="score_path"       class="score_value">53</div>
            <div id="score_disdain"    class="score_value score_bad">63</div>
            <div id="score_total"      class="score_value">73</div>

            <div id="disdain_circle"   class="disdain_circle_0"></div>
        </div>
        <div id="sheet1_selector" class="forcehidden"></div>
    </div>
    <div id="sheet2">
        <div id="trade_options" class="forcehidden">
            <div id="trade_left" class="iconsheet icon_trade_good">#</div>
            <div id="trade_fate" class="iconsheet icon_trade_good">?</div>
            <div id="trade_right" class="iconsheet icon_trade_good">#</div>
        </div>
        <div id="scout_options">
            <div id="scout_left" class="iconsheet icon_scout"></div>
            <div id="scout_fate" class="iconsheet icon_scout"></div>
            <div id="scout_right" class="iconsheet icon_scout"></div>
        </div>
        <div id="sheet2_selector" class="forcehidden"></div>
    </div>

    <div id="hand" class="forcehidden">
    </div>

    <div id="fate" class="forcehidden">
        <div id="fate_card" class="fatecardsheet fate_card_9 card_in_hand"></div>
        <div id="fate_cover_attack" class="forcehidden_"></div>
        <div id="fate_cover_gladiator" class="forcehidden_"></div>
        <div id="fate_cover_trade_good" class="forcehidden_"></div>
        <div id="fate_cover_resources" class="forcehidden"></div>
    </div>

    <div id="production" class="forcehidden">
        <div class="iconsheet icon_brick"></div>
        <div class="iconsheet icon_brick"></div>
    </div>

    <div id="attack" class="forcehidden">
        <div id="attack_left" class="attack_column">
        </div>
        <div id="attack_center" class="attack_column">
        </div>
        <div id="attack_right" class="attack_column">
        </div>
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
                <div class="iconsheet icon_disdain miniicon"></div>
                <div id="disdain_score_{COLOR}"  class="attribute_score"></div>
            </div>

            <div></div>
            <div id="path_cards_attribute" class="attribute_backing" style="display: flex; flex-direction: row;">
                <div style="width: 76px;">
                <div id="path1_{COLOR}" class="playercardsheet player_back_{COLOR} card_banner_only "></div>
                <div id="path4_{COLOR}" class="playercardsheet player_back_{COLOR} card_banner_only "></div>
                </div>
                <div style="width: 76px;">
                <div id="path2_{COLOR}" class="playercardsheet player_back_{COLOR} card_banner_only "></div>
                <div id="path5_{COLOR}" class="playercardsheet player_back_{COLOR} card_banner_only "></div>
                </div>
                <div style="width: 76px;">
                <div id="path3_{COLOR}" class="playercardsheet player_back_{COLOR} card_banner_only "></div>
                <div id="path6_{COLOR}" class="playercardsheet player_back_{COLOR} card_banner_only "></div>
                </div>
            </div>

        </div>
    </div>
    <!-- END player_board -->

    <!-- BEGIN my_player_board -->
    <div id="miniboard_{COLOR}" class="resourceboard">


        <div id="speical_display" class="forcehidden">
            <div class="iconsheet icon_trader miniicon glow"></div>
            <div class="iconsheet icon_priest miniicon glow"></div>
            <div class="iconsheet icon_apparitor miniicon glow"></div>
            <div class="iconsheet icon_renown miniicon glow"></div>
            <div class="iconsheet icon_discipline miniicon glow"></div>
        </div>

        <div id="resource_display" class="">
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
        </div>

        <div id="path_cards_attribute" class="attribute_backing" style="display: flex; flex-direction: row;">
            <div style="width: 114px;">
            <div id="path1_{COLOR}" class="playercardsheet player_back_{COLOR} card_top_only "></div>
            <div id="path3_{COLOR}" class="playercardsheet player_back_{COLOR} card_top_only "></div>
            <div id="path5_{COLOR}" class="playercardsheet player_back_{COLOR} card_top_only "></div>
            </div>
            <div style="width: 114px;">
            <div id="path2_{COLOR}" class="playercardsheet player_back_{COLOR} card_top_only "></div>
            <div id="path4_{COLOR}" class="playercardsheet player_back_{COLOR} card_top_only "></div>
            <div id="path6_{COLOR}" class="playercardsheet player_back_{COLOR} card_top_only "></div>
            </div>
        </div>

    </div>
    <!-- END my_player_board -->

</div>


<div id="playerboard1"  class="forcehidden">
    <div id="round_1_path" class="playercardsheet player_card_1 path path_round_1 hidden"></div>
    <div id="round_2_path" class="playercardsheet player_card_7 path path_round_2 hidden"></div>
    <div id="round_3_path" class="playercardsheet player_card_3 path path_round_3 hidden"></div>
</div>

<div id="playerboard2" class="forcehidden">
    <div id="round_4_path" class="playercardsheet player_card_4 path path_round_4 hidden"></div>
    <div id="round_5_path" class="playercardsheet player_card_5 path path_round_5 hidden"></div>
    <div id="round_6_path" class="playercardsheet player_card_6 path path_round_6 hidden"></div>
</div>


<script type="text/javascript">

// Javascript HTML templates
var jstpl_scratch='<div id="${id}" class="tooltip clickable outlined ${c}" style="position:absolute;left:${x}px;top:${y}px;width:${w}px;height:${h}px;">${value}<span class="${ttc}">${tt}</span></div>';


</script>  

{OVERALL_GAME_FOOTER}
