<?php
    require_once(APPPATH . 'libraries/twilio.php');

    if(!OpenVBX::isAdmin()){
        die();
    }
     
   $users = OpenVBX::getUsers();

   if(isset($_POST["sub"])){
       foreach($_POST["users"] as $id => $user){
            $conf = array("phone" => $user["phone"], "extension" => (int)$user["ext"]);
            PluginData::set("users-$id", $conf);

            $ext["e-" . $user["ext"]] = $user["phone"]; // Stupid PHP/OpenVBX hack. This will come out as a StdObject, and when we cast it to an array, index will always be a string. PHP won't let us access a string-int index with an int.
       }

       PluginData::set("extensions", $ext);
   }
?>
 
<!-- @start snippet -->
<div class="vbx-plugin">
     
    <h3>Users</h3>
    <form method="post">
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Primary Phone</th>
                    <th>Extension</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach($users as $user): $user = (array)$user; $options = (array)PluginData::get("users-{$user["values"]["id"]}"); ?>
                <tr>
                    <td><?php echo "{$user["values"]["first_name"]} {$user["values"]["last_name"]}"; ?></td>
                    <td>
                        <select name="users[<?php echo $user["values"]["id"]; ?>][phone]">
                            <option value="">(none)</option>
                            <?php foreach($user["devices"] as $device) : $device = (array)$device; ?>
                                <option value="<?php echo $device["values"]["value"]; ?>"<?php if(isset($options["phone"]) && $options["phone"] == $device["values"]["value"]) echo ' selected="true"'; ?>><?php echo "{$device["values"]["name"]} (" . format_phone($device["values"]["value"]) . ")"; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td><input type="text" name="users[<?php echo $user["values"]["id"]; ?>][ext]" value="<? if(isset($options["extension"])) echo $options["extension"];?>"></td>
                </tr>
            <?php endforeach ?>
            </tbody>
        </table>
        <input type="submit" name="sub" value="Submit changes" />
    </form>
 
</div>
<!-- @end snippet --> 
?>