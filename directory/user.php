<?php
    require_once(APPPATH . 'libraries/twilio.php');
     
   $user = (array)OpenVBX::getCurrentUser();
   $options = (array)PluginData::get("users-{$user["values"]["id"]}");

   if(isset($_POST["sub"])){
        $conf = array("phone" => $_POST["phone"], "extension" => $options["extension"]);
        PluginData::set("users-{$user["values"]["id"]}", $conf);
   }

   $options = (array)PluginData::get("users-{$user["values"]["id"]}");
?>
 
<!-- @start snippet -->
<div class="vbx-plugin">
     
    <h3>My Directory Information</h3>
    <form method="post">
        <table>
            <thead>
                <tr>
                    <th>Primary Phone</th>
                    <th>Extension</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <select name="phone">
                            <option value="">(none)</option>
                            <?php foreach($user["devices"] as $device) : $device = (array)$device; ?>
                                <option value="<?php echo $device["values"]["value"]; ?>"<?php if(isset($options["phone"]) && $options["phone"] == $device["values"]["value"]) echo ' selected="true"'; ?>><?php echo "{$device["values"]["name"]} (" . format_phone($device["values"]["value"]) . ")"; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td><? if(isset($options["extension"])) echo $options["extension"];?></td>
                </tr>
            </tbody>
        </table>
        <input type="submit" name="sub" value="Submit changes" />
    </form>
 
</div>
<!-- @end snippet --> 
?>