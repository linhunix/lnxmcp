<?php
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

              echo '<label for="ext' . $rowExtraFields['id'] . '">' . functions::clean_file_name($rowExtraFields['title']) . '</label>';
                if ($rowExtraFields['regtype'] == 0) {
                    echo '<div class="extrafieldinfo" >This field is for all registrant types</div>';
                } else {
                    echo '<div class="extrafieldinfo" >This field is only for registrants of type "' . $rowExtraFields['rttitle'] . '"</div>';
                }
                echo "<div class='formelementcontainer'>";
                switch ($rowExtraFields['type']) {
                    case "text":
                        echo '<input class="dontfloat" id="ext' . $rowExtraFields['id'] . '" type="text" name="' . $functions->clean_string_database($rowExtraFields['title']) . '" value="' . $fieldDetails[$functions->clean_string_database($rowExtraFields['title'])] . '" /><br/>';
                        break;
                    case "textarea":
                        echo '<textarea class="dontfloat" id="ext' . $rowExtraFields['id'] . '" name="' . $functions->clean_string_database($rowExtraFields['title']) . '" cols="80" rows="5">' . $fieldDetails[$functions->clean_string_database($rowExtraFields['title'])] . '</textarea><br/>';
                        break;
                    case "checkbox":
                        if ("yes" == $fieldDetails[$functions->clean_string_database($rowExtraFields['title'])]) {
                            $sel = 'checked="checked"';
                        } else {
                            $sel = '';
                        }
                        echo '<input class="dontfloat" id="ext' . $rowExtraFields['id'] . '" type="checkbox" name="' . $functions->clean_string_database($rowExtraFields['title']) . '" value="yes" ' . $sel . '/><br/>';
                        break;
                    case "radio":
                        $values = explode(";", $rowExtraFields['fieldvalues']);
                        foreach ($values as $value) {
                            if ($value == $fieldDetails[$functions->clean_string_database($rowExtraFields['title'])]) {
                                $sel = 'checked="checked"';
                            } else {
                                $sel = '';
                            }
                            echo '<input class="dontfloat" id="ext' . $rowExtraFields['id'] . '" type="radio" name="' . $functions->clean_string_database($rowExtraFields['title']) . '" value="' . $value . '" ' . $sel . ' /> ' . $rowExtraFields['title'] . ' ';
                        }
                        break;
                    case "select":
                        echo '<select class="dontfloat" id="ext' . $rowExtraFields['id'] . '" name="' . $functions->clean_string_database($rowExtraFields['title']) . '">';
                        $values = explode(";", $rowExtraFields['fieldvalues']);

                        foreach ($values as $value) {
                            if ($value == $fieldDetails[$functions->clean_string_database($rowExtraFields['title'])]) {
                                $sel = 'selected="selected"';
                            } else {
                                $sel = '';
                            }
                            echo '<option value="' . $value . '" ' . $sel . '>' . $value . '</option>';
                        }
                        echo '</select>';
                        break;
                    case "selectmulti":
                        echo '<select class="dontfloat" id="ext' . $rowExtraFields['id'] . '" name="' . $functions->clean_string_database($rowExtraFields['title']) . '[]" multiple>';
                        $values = explode(";", $rowExtraFields['fieldvalues']);
                        $fieldDetailsExploded = explode(';', $fieldDetails[$functions->clean_string_database($rowExtraFields['title'])]);
                        foreach ($values as $value) {
                            if (in_array($value, $fieldDetailsExploded)) {
                                $sel = 'selected="selected"';
                            } else {
                                $sel = '';
                            }
                            echo '<option value="' . $value . '" ' . $sel . '>' . $value . '</option>';
                        }
                        echo '</select>';
                        echo '<div class="dontfloat">Ctrl+Click to select more than one</div>';
                        break;
                }
                echo "</div><br/>";