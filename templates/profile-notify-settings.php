<?php 
    global $upuna_settings_data; 
    extract($upuna_settings_data);
    $notification_fields = $notification_fields_list;
    $filtered_fields = array('user_pic','user_pass','user_pass_confirm');

    $profile_fields = get_option('upme_profile_fields');

?>

<form method="post" action="">
<table class="form-table">
    
                <tr>
                    <th><label for=""><?php _e('Profile Notification Fields','upuna'); ?></label></th>
                    <td style="width:500px;">
                    <select  name="upuna_notify[notification_fields_list][]" multiple class="chosen-admin_setting">
                        <option <?php echo in_array('0',$notification_fields)? 'selected' : ''; ?> value="0" ></option>
                        <?php foreach($profile_fields as $k => $field){
                            if($field['type'] == 'usermeta' && !in_array($field['meta'],$filtered_fields)){
                                $selected = '';
                                if(in_array($field['meta'],$notification_fields)){
                                    $selected = 'selected';
                                }
                        ?>
                            <option <?php echo $selected; ?>  value="<?php echo $field['meta']; ?>" ><?php echo $field['name']; ?></option>
                        <?php
                            }            
                        }
                        ?>
                    </select>
                    
                </td>
                </tr>                
                
    
    <input type="hidden" name="upuna_tab" value="<?php echo $tab; ?>" />

    
</table>
    <?php submit_button(); ?>
</form>