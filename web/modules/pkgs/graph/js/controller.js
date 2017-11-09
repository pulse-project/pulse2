/**
 * (c) 2016 Siveo, http://www.siveo.net/
 *
 * $Id$
 *
 * This file is part of Pulse 2, http://www.siveo.net/
 *
 * Pulse 2 is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * Pulse 2 is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with Pulse 2; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA 02110-1301, USA.
 *
 */


/**
 *
 * LOAD AVIABLE ACTIONS
 *
 */
jQuery(function(){
    jQuery.each(actionsList, function(id, actionName){
        jQuery("#available-actions").append(jQuery(document.createElement("li")).load("/mmc/modules/pkgs/includes/actions/"+actionName+".php"));
    });
})


/**
 *
 * ACTIONS ARE SORTABLE
 */
jQuery( function() {
    jQuery( "#current-actions" ).sortable({
        revert: true
    });
    jQuery( "#available-actions li" ).draggable({
        cursor: "move",
        connectToSortable: "#current-actions",
        helper: "clone",
        revert: "invalid",
        start  : function(event, ui){
            jQuery(ui.helper).css("width",'100%');
            jQuery(ui.helper).css("height",'100%');
            jQuery(ui.helper).children('.content').children("div").find("input[name='actionlabel']").val(uuid);
        }
    });
    jQuery( "ul, li" ).disableSelection();
} );


/**
 *
 * INITIALIZE WORKFLOWS
 * @see pkgs/graph/js/class.js
 *
 */
jQuery(function(){

    if(typeof(jQuery("#loadJson").val()) != "undefined" && jQuery("#loadJson").val() != "")
    {// If something into #loadJson = edit mode

        tmp = JSON.parse(jQuery("#loadJson").val());

        //Set transferfile value with the saved value
        if(tmp['info']['transferfile'] == true)
            jQuery('#transferfile option[value=1]').attr("selected",true);
        else
            jQuery('#transferfile option[value=0]').attr("selected",true);

        //Set methodtransfer value with the saved value
        jQuery('#methodetransfert option[value="'+tmp['info']['methodetransfert']+'"]').attr("selected",true);

        //Get the elements of the sequence
        sequence = getSequenceFromJSON(tmp);


        jQuery.each(sequence, function(id,action){
            jQuery("#current-actions").append(jQuery(document.createElement("li")).load("/mmc/modules/pkgs/includes/actions/"+action['action']+".php", action));

        });
    }
    else
    {
        // Else new package : add actionsuccescompletedend and actionerrorcompletedend to the flow
        var actions = ['actionsuccescompletedend','actionerrorcompletedend'];
        jQuery.each(actions, function(id,action){
            jQuery("#current-actions").append(jQuery(document.createElement("li")).load("/mmc/modules/pkgs/includes/actions/"+action+".php"));
        });
    }
});


//Add selected dependencies into dependencies list of the json
function moveToLeft()
{
    selectedDependencies = jQuery("#pooldependencies").val();

    jQuery.each(selectedDependencies, function(id,dependency){
        jQuery("#addeddependencies").append(jQuery("#pooldependencies").find("[value="+dependency+"]")[0]);
    });

}

//Remove selected dependencies from dependencies list of the json
function moveToRight()
{
    selectedDependencies = jQuery("#addeddependencies").val();
    jQuery.each(selectedDependencies, function(id,dependency){
        jQuery("#pooldependencies").append(jQuery("#addeddependencies").find("[value="+dependency+"]")[0]);
    });

}


// Get all the workflow elements and create a sequence
function createSequence()
{
    // Create a new sequence
    var sequence = [];

    /**
     * Get all the form element in #current-actions and serialize them
     */
    // For each action :
    jQuery.each(jQuery("#current-actions").children(),function(id,element){
        datas = jQuery(element).children('div').find("input,textarea,select").serializeArray();

        // Create new action array
        var action = {};

        // For each element in form :
            // Add {form.elementName : form.elementValue} to action
        jQuery.each(datas,function(idoption, actionRaw){
            action[actionRaw['name']] = actionRaw['value'];
        });
        // Add {step:increment} to this action
        action['step'] = id;

        // Then the sequence is created
        sequence.push(action);
    });

    return sequence;
}


// Get info from interface and return it as json
function createInfo()
{
    var info = {};

    // Manage dependencies
    info['Dependency'] = []
    jQuery.each(jQuery("#addeddependencies").children('option'),function(id,dependency){
        info['Dependency'].push(jQuery(dependency).val());
    });

    datas = jQuery("#Form").serializeArray();

    jQuery.each(datas, function(id,param){
        if(param['name'] == "label"){
            info['name'] = param['value'];
        }

        else
            if(param['name'] != "saveList")
            {
                if(param['name'] == 'transferfile')
                {
                    if(param['value'] == 1)
                        info[param['name']] = true;

                    else
                        info[param['name']] = false;
                }
                else if (param['name'] == 'Dependency' | param['name'] == 'members[]' | )) {
                    //Managed before outside this loop because to manage it is special
                }
                else
                    info[param['name']] = param['value'];
            }
    });
    return info;
}

/**
 * Get info json and sequence json and return workflow json
 * @return the workfows json
 */
function createJson()
{
    var json = {};

    json['info'] = createInfo();
    json[json['info']['targetos']] = {};

    json[json['info']['targetos']]['sequence'] = {};
    json[json['info']['targetos']]['sequence'] = createSequence();

    return json;
}
function getJSON()
{
    jQuery("input[name='saveList']").val(JSON.stringify(createJson()));
    console.log(jQuery("input[name='saveList']").val());
}

function getSequenceFromJSON(json)
{
    var actionList = [];
    jQuery.each(json, function(key, obj){
        if(key != "info")
        {
            actionList = json[key]['sequence'];
        }
    });
    return actionList;
}


function getActionsList()
{
    jQuery.each(jQuery("#current-actions").children(),function(id,element){
        datas = jQuery(element).children('form').serializeArray();

        // Create new action array
        var action = {};

        // For each element in form :
        // Add {form.elementName : form.elementValue} to action
        jQuery.each(datas,function(idoption, actionRaw){
            if(actionRaw['name'] == "actionlabel")
                action[actionRaw['name']] = actionRaw['value'];
        });
        // Add {step:increment} to this action
        action['step'] = id;

        // Then the sequence is created
        sequence.push(action);
    });

}

function uuid() {
    return (((1+Math.random())*0x10000)|0).toString(16).substring(1)+(((1+Math.random())*0x10000)|0).toString(16).substring(1);
}
