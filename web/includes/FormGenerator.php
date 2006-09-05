<?php
/**
 * (c) 2004-2006 Linbox / Free&ALter Soft, http://linbox.com
 *
 * $Id$
 *
 * This file is part of LMC.
 *
 * LMC is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * LMC is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with LMC; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */
?>
<?php
/***********************************************************************
 *  Form generator class
 ***********************************************************************/

function displayErrorCss($name) {
  global $formErrorArray;
  if ($formErrorArray[$name]==1) {
    print ' style="color: #C00; text-align:right;"';
    }
}


/**
 * Checkbox input template
 */
class CheckboxTpl extends AbstractTpl{


  function CheckboxTpl($name) {
    $this->name=$name;
  }

  /**
   *  display input Element
   *  $arrParam accept ["value"] to corresponding value
   */
  function display($arrParam) {
    print '<input '.$arrParam["value"].' name="'.$this->name.'" id="'.$this->name.'" type="checkbox" class="checkbox" '.$arrParam["extraArg"].' />';
  }

   function displayRo($arrParam) {

    if ($arrParam["value"]=="checked") {
      $value="on";
      print "oui";
    }
    else {
      print "non";
    }
    print '<input  type="hidden" value="'.$value.'" name="'.$this->name.'">';
  }

  function displayHide($arrParam) {
  if ($arrParam["value"]=="checked") {
      $value="on";
      }
    print '<div style="color: #C00;">indisponible</div>';
    print '<input  type="hidden" value="'.$value.'" name="'.$this->name.'">';
  }
}

/**
 * password input template
 */
class PasswordTpl extends AbstractTpl{


  function PasswordTpl($name) {
    $this->name=$name;
  }

  /**
   *  display input Element
   *  $arrParam accept ["value"] to corresponding value
   */
  function display($arrParam) {
    print '<input name="'.$this->name.'" type="password" class="textfield" size="23" '.$arrParam["disabled"].' />';
  }
}


/**
 * simple input template
 */
class InputTpl extends AbstractTpl{

  function InputTpl($name,$regexp='/.*/') {
    $this->name=$name;
    $this->regexp = $regexp;
  }

  /**
   *  display input Element
   *  $arrParam accept ["value"] to corresponding value
   */
  function display($arrParam) {
    if ($arrParam=='') {
        $arrParam = $_POST[$this->name];
    }
    print '<span id="container_input_'.$this->name.'"><input name="'.$this->name.'" id="'.$this->name.'" type="text" class="textfield" size="23" value="'.$arrParam["value"].'" '.$arrParam["disabled"].' /></span>';

    print '<script>
                $(\''.$this->name.'\').validate = function() {
                    if ($(\''.$this->name.'\').value == \'\') { //if is empty (hidden value)
                        return true
                    }
                    var rege = '.$this->regexp.'
                    if ((rege.exec($(\''.$this->name.'\').value))!=null) {
                        return true
                    } else {
                        $(\''.$this->name.'\').style.backgroundColor = \'pink\';
                        new Element.scrollTo(\'container_input_'.$this->name.'\');
                        return 0;
                    }
                }
           </script>';
  }
}

/**
 * simple add label with Hidden field
 */
class HiddenTpl extends AbstractTpl{

  function HiddenTpl($name) {
    $this->name=$name;
  }

  /**
   *  display input Element
   *  $arrParam accept ["value"] to corresponding value
   */
  function display($arrParam) {
    if ($arrParam=='') {
        $arrParam = $_POST[$this->name];
    }
    print $arrParam['value'].'<input  type="hidden" value="'.$arrParam["value"].'" name="'.$this->name.'"/>';

  }
}


class MultipleInputTpl extends AbstractTpl {

    function MultipleInputTpl($name,$desc='') {
       $this->name = $name;
       $this->desc = $desc;
       $this->regexp = '/.*/';
    }

    function setRegexp($regexp) {
       $this->regexp = $regexp;
    }

    function display($arrParam) {
        print '<div id="'.$this->name.'">';
        print '<table>';
        foreach ($arrParam as $key => $param) {
              $test = new DeletableTrFormElement($this->desc,
                                                 new InputTpl($this->name.'['.$key.']',$this->regexp),
                                                 array('key'=>$key,
                                                       'name'=> $this->name)
                                                 );
              $test->setCssError($name.$key);
              $test->display(array("value"=>$param));
        }
        print '<tr><td width="40%" style="text-align:right;">';
        if (count($arrParam) == 0) {
            print $this->desc;
        }
        print '</td><td>';
        print '<input name="buser" type="submit" class="btnPrimary" value="'._("Add").'" onClick="
        new Ajax.Updater(\''.$this->name.'\',\'includes/FormGenerator/MultipleInput.tpl.php\',
        { parameters: Form.serialize($(\'edit\'))+\'&minputname='.$this->name.'&desc='.urlencode($this->desc).'\' }); return false;"/>';
        print '</td></tr>';
        print '</table>';
        print '</div>';
    }

   function displayRo($arrParam) {
               print '<div id="'.$this->name.'">';
        print '<table>';
        foreach ($arrParam as $key => $param) {
              $test = new DeletableTrFormElement($this->desc,
                                                 new InputTpl($this->name.'['.$key.']',$this->regexp),
                                                 array('key'=>$key,
                                                       'name'=> $this->name)
                                                 );
              $test->setCssError($name.$key);
              $test->displayRo(array("value"=>$param));
        }
        if (count($arrParam) == 0) {
            print '<tr><td width="40%" style="text-align:right;">';
            print $this->desc;
            print '</td><td>';
            print '</td></tr>';
        }
        print '</table>';
        print '</div>';
   }

   function displayHide($arrParam) {
        print '<div id="'.$this->name.'">';
        print '<table>';
        print '<tr><td width="40%" style="text-align:right;">'.$this->desc.'</td>';
        print '<td style="color: rgb(204, 0, 0);">'.'indisponible'.'</td></tr>';
        print '</table>';
        print '<div style="display:none">';
        print '<table>';
        foreach ($arrParam as $key => $param) {
              $test = new DeletableTrFormElement($this->desc,
                                                 new InputTpl($this->name.'['.$key.']',$this->regexp),
                                                 array('key'=>$key,
                                                       'name'=> $this->name)
                                                 );
              $test->setCssError($name.$key);
              $test->displayHide(array("value"=>$param));
        }
        if (count($arrParam) == 0) {
            print '<tr><td width="40%" style="text-align:right;">';
            print $this->desc;
            print '</td><td>';
            print '</td></tr>';
        }
        print '</table>';
        print '</div>';
        print '</div>';
   }


}

/**
 *  astract class template
 */
class AbstractTpl {
  var $name;
  /**
   *  display abstract Element
   *  $arrParam accept ["value"]
   */
  function display($arrParam) {
  }

  /**
   *  Read Only display function
   */
  function displayRo($arrParam) {
    print $arrParam["value"];
    print '<input  type="hidden" value="'.$arrParam["value"].'" name="'.$this->name.'">';
  }

  function displayHide($arrParam) {
    print '<div style="color: #C00;">indisponible</div>';
    print '<input  type="hidden" value="'.$arrParam["value"].'" name="'.$this->name.'">';
  }
}


/**
 *  display select html tags with specified
 *  entry, autoselect.
 */
class SelectItem extends AbstractTpl{
  var $elements; /**< list of all elements*/
  var $elementsVal; /**< list of elements values*/
  var $selected; /**< element who are selected*/
  var $id; /**< id for css property*/

  /**
   *constructor
   */
  function SelectItem($idElt) {
  $this->id=$idElt;
  $this->name=$idElt;
  }

  function setElements($elt) {
    $this->elements= $elt;
  }

  function setElementsVal($elt) {
    $this->elementsVal= $elt;
  }

  function setSelected($elemnt) {
    $this->selected= $elemnt;
  }

  /**
   * $paramArray can be "null"
   */
  function display($paramArray = null) {

    // if value... set it
    if ($paramArray["value"]) {
      $this->setSelected($paramArray["value"]);
    }

    print "<select name=\"".$this->id."\">\n";
    foreach ($this->elements as $key => $item) {

      if ($item==$this->selected) {
        $selected="selected";
      }
      else {
        $selected= "";
      }
      if ($this->elementsVal) {
      print "\t<option value=\"".$this->elementsVal[$key]."\" $selected>$item</option>\n";
      }
      else {
      print "\t<option value=\"$item\" $selected>$item</option>\n";
      }
    }

    print "</select>\n";
  }

}


/**
 * Simple Form Template encapsulator
 *
 */
class FormElement {
  var $template;
  var $desc;
  var $cssErrorName;

  function FormElement($desc,$tpl) {
    $this->desc=$desc;
    $this->template=&$tpl;
  }


  function setCssError($name) {
    $this->cssErrorName=$name;
  }

  /**
   *  display input Element
   *  $arrParam accept ["value"] to corresponding value
   */
  function display($arrParam) {

    $existACL=existAclAttr($this->template->name);

    //if not
    if (!$existACL) {
      $aclattrright="rw";
      $isAclattrright=true;
    } else {
      $aclattrright=(getAclAttr($this->template->name));
      $isAclattrright=$aclattrright!='';
    }

    //if correct acl and exist acl
    if ($isAclattrright) {
      //if read only
      if ($aclattrright=="ro") {
        $this->template->displayRo($arrParam);
        //if all right
      } else if ($aclattrright=="rw") {
        $this->template->display($arrParam);
      }
      //if no right at all
    } else {
      $this->template->displayHide($arrParam);
    }

  }
  function displayRo($arrParam) {
      $this->template->displayRo($arrParam);
  }

  function displayHide($arrParam) {
      $this->template->displayHide($arrParam);
  }
}


/**
 * display a tr html tag in a form
 * using corresponding template
 */
class DeletableTrFormElement extends FormElement{
  var $template;
  var $desc;
  var $cssErrorName;

  function DeletableTrFormElement($desc,$tpl,$extraInfo = array()) {
    $this->desc=$desc;
    $this->template=&$tpl;
    foreach ($extraInfo as $key => $value) {
        $this->$key = $value;
    }
  }

  /**
   *  display input Element
   *  $arrParam accept ["value"] to corresponding value
   */
  function display($arrParam) {

    if ($this->key==0) {
        $desc = $this->desc;
    }
    print '<tr><td width="40%" ';
    print displayErrorCss($this->cssErrorName);
    print 'style = "text-align: right;">';

    //if we got a tooltip, we show it
    if ($this->tooltip) {
        print "<a href=\"#\" class=\"tooltip\">".$desc."<span>".$this->tooltip."</span></a>";
    } else {
        print $desc;
    }
    print '</td><td>';

    parent::display($arrParam);
    print '<input name="bdel" type="submit" class="btnSecondary" value="'._("Delete").'" onClick="
        new Ajax.Updater(\''.$this->name.'\',\'includes/FormGenerator/MultipleInput.tpl.php\',
        { parameters: Form.serialize($(\'edit\'))+\'&minputname='.$this->name.'&del='.$this->key.'&desc='.urlencode($this->desc).'\' }); return false;"/>';


    print '</td></tr>';


  }

  function displayRo($arrParam) {

    if ($this->key==0) {
        $desc = $this->desc;
    }
    print '<tr><td width="40%" ';
    print displayErrorCss($this->cssErrorName);
    print 'style = "text-align: right;">';

    //if we got a tooltip, we show it
    if ($this->tooltip) {
        print "<a href=\"#\" class=\"tooltip\">".$desc."<span>".$this->tooltip."</span></a>";
    } else {
        print $desc;
    }
    print '</td><td>';

    parent::displayRo($arrParam);

    print '</td></tr>';


  }
}

/**
 * display a tr html tag in a form
 * using corresponding template
 */
class TrFormElement extends FormElement{
  var $template;
  var $desc;
  var $cssErrorName;

  function TrFormElement($desc,$tpl,$extraInfo = array()) {
    $this->desc=$desc;
    $this->template=&$tpl;
    foreach ($extraInfo as $key => $value) {
        $this->$key = $value;
    }
  }


  /**
   *  display input Element
   *  $arrParam accept ["value"] to corresponding value
   */
  function display($arrParam) {

    print '<tr><td width="40%" ';
    print displayErrorCss($this->cssErrorName);
    print 'style = "text-align: right;">';

    //if we got a tooltip, we show it
    if ($this->tooltip) {
        print "<a href=\"#\" class=\"tooltip\">".$this->desc."<span>".$this->tooltip."</span></a>";
    } else {
        print $this->desc;
    }
    print '</td><td>';

    parent::display($arrParam);

    print '</td></tr>';
  }

  function displayRo($arrParam) {

    print '<tr><td width="40%" ';
    print displayErrorCss($this->cssErrorName);
    print 'style = "text-align: right;">';

    //if we got a tooltip, we show it
    if ($this->tooltip) {
        print "<a href=\"#\" class=\"tooltip\">".$this->desc."<span>".$this->tooltip."</span></a>";
    } else {
        print $this->desc;
    }
    print '</td><td>';

    parent::displayRo($arrParam);

    print '</td></tr>';
  }
}

class Form {

    function begin() {
        print '<form method="POST">';
    }

    function beginTable() {
        print '<table cellspacing="0">';
    }

    function endTable() {
        print '</table>';
    }

    function end() {
        print '</form>';
    }

}

?>