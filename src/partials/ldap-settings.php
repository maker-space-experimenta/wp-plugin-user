<?php

$ldap_server = get_option("makerspace_ldap_server");
$ldap_port = get_option("makerspace_ldap_port");
$ldap_admin = get_option("makerspace_ldap_admin");
$ldap_password = get_option("makerspace_ldap_admin_pass");

?>



<h1>LDAP Settings</h1>

<form method="post" action="options.php" novalidate="novalidate">

    <table class="form-table">

        <tbody>
            <tr>
                <th scope="row"><label for="ms_options_ldap_server">LDAP Server</label></th>
                <td>
                    <input name="ms_options_ldap_server" type="text" id="ms_options_ldap_server" value="<?php echo $ldap_server ?>" class="regular-text">
                </td>
            </tr>

            <tr>
                <th scope="row"><label for="ms_options_ldap_port">LDAP Port</label></th>
                <td>
                    <input name="ms_options_ldap_port" type="number" id="ms_options_ldap_port" value="<?php echo $ldap_port ?>" class="regular-text">
                </td>
            </tr>


            <tr>
                <th scope="row"><label for="ms_options_ldap_admin">LDAP-Admin</label></th>
                <td><input name="ms_options_ldap_admin" type="text" id="ms_options_ldap_admin" value="<?php echo $ldap_admin ?>" class="regular-text code"></td>
            </tr>

            <tr>
                <th scope="row"><label for="ms_options_ldap_pass">Password</label></th>
                <td>
                    <input name="ms_options_ldap_pass" type="password" id="ms_options_ldap_pass" value="<?php echo $ldap_password ?>" class="regular-text code">
                </td>
            </tr>

        </tbody>
    </table>


    <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes" disabled></p>
</form>