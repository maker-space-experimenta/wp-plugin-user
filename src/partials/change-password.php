<?php

$error = false;
$success = false;
$debug = true;

if(isset($_POST["makerspace-user-password"])):

    if ($_POST["makerspace-user-password-repeat"] != $_POST["makerspace-user-password"]) {
        $error = "Das neue Passwort wurde nicht korrekt wiederholt.";
    }

    if (!$error):
        $oldPW = "{MD5}" . base64_encode(pack("H*", md5($_POST["makerspace-old-password"])));
        $newPW = $_POST["makerspace-user-password"];

        $user = wp_get_current_user();

        $ldap['server'] =       get_option("makerspace_ldap_server");
        $ldap['port'] =         get_option("makerspace_ldap_port");
        $ldap['admin'] =        get_option("makerspace_ldap_admin");
        $ldap['admin_pass'] =   get_option("makerspace_ldap_admin_pass");
        $ldap['user_ou'] =      get_option("makerspace_ldap_user_ou");

        $ldap['connection'] = ldap_connect( $ldap['server'], $ldap['port'] );
        ldap_set_option($ldap['connection'], LDAP_OPT_PROTOCOL_VERSION, 3);

        if ($ldap['connection']):
            $ldap_binding = ldap_bind( $ldap['connection'], $ldap['admin'], $ldap['admin_pass'] )  or die ("Error trying to bind: ".ldap_error($ldap['connection']));

            if ($ldap_binding):
                $search = ldap_search($ldap['connection'], $ldap['user_ou'], "(cn=".$user->user_login .")");
                $ldapentry = ldap_first_entry ($ldap['connection'], $search);

                $dn = 'cn=' . $user->user_login . ',' . $ldap['user_ou'];


                $r=ldap_compare($ldap['connection'], $dn, 'userPassword',$oldPW);

                if ($r === -1) {
                    $error = ldap_error($ds);
                } elseif ($r === true) {
                    $entry = array('userPassword' => "{MD5}" . base64_encode(pack("H*", md5($newPW))));

                    if (ldap_mod_replace($ldap['connection'], $dn, $entry)) {
                        $success = true;
                        add_user_meta( $user->ID, 'ms-should-change-password', "false", true );
                    } else {
                        $error = "Fehler beim Passwort setzten. (" . ldap_error($ldap['connection']) . ")";
                    }
                } elseif ($r === false) {
                    $error = "Das aktuelle Passwort ist nicht korrekt.";
                }

            endif;
        endif;
    endif;
endif;

?>

<h1 class="ms-side-headline">Passwort ändern</h1>


<?php if ($error): ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <div class="card border border-danger p-1" style="border-left-width: 5px !important; min-width: 100%; max-width: 100%;">
                    <div class="card-body">
                        <?php echo $error; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php if ($success): ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <div class="card border border-success p-1" style="border-left-width: 5px !important; min-width: 100%; max-width: 100%;">
                    <div class="card-body">
                        Das Passwort wurde erfolgreich geändert.
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>




<form method="post" action="/wp-admin/users.php?page=makerspace_change_user_password">

    <?php wp_nonce_field( basename( __FILE__ ), 'metabox_devices_nonce' ); ?>

    <div class="container-fluid mt-5">
        <div class="row">
            <div class="col">
                <div class="col">
                    <div class="form-group">
                        <label for="makerspace-old-password">Aktuelles Passwort</label>
                        <input type="password" class="form-control" id="makerspace-old-password" name="makerspace-old-password" placeholder="Aktuelles Passwort" required>
                    </div>
                    <hr />
                    <div class="form-group">
                        <label for="makerspace-user-password">Neues Password</label>
                        <input type="password" class="form-control" id="makerspace-user-password" name="makerspace-user-password" placeholder="Neues Password" required>
                    </div>
                    <div class="form-group">
                        <label for="makerspace-user-password-repeat">Neues Password wiederholen</label>
                        <input type="password" class="form-control" id="makerspace-user-password-repeat" name="makerspace-user-password-repeat" placeholder="Neues Password wiederholen" required>
                    </div>
                    <div class="d-flex">
                        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                            Wie wähle ich ein sicheres Passwort?
                        </button>
                        <button type="submit" class="ml-auto btn btn-primary">Speichern</button>
                    </div>

                </div>
            </div>
            <div class="col">

                <p>

                </p>
                <div class="collapse" id="collapseExample">
                    <div class="card card-body" style="min-width: 100%; max-width: 100%;">

                        <h2>Was wird mit dem Passwort gesichert?</h2>
                        <p>
                            Bitte verwende ein möglichst sicheres Passwort. Über dieses Passwort wird dein Maker Space Account geschützt.
                            Ein unsicheres Passwort kann dazu führen, dass fremde Menschen an deine Daten kommen.
                        </p>
                        <p>
                            Zu deinen Daten zählen neben deinen Tischkicker-Scores auch deine Blogeinträge hier aber auch die Bilder die auf deinem Speicher abgelegt sind.
                        </p>

                        <h2>Was macht ein Passwort sicher?</h2>
                        <p>
                            Ein sicheres Passwort sollte möglichst nicht zu erraten sein.
                            Daher sollte es nicht der Name deiner Haustiere, Eltern, Freunde oder anderer Persönlichkeiten sein.
                        </p>
                        <p>
                            Ein sicheres Passwort sollte möglichst lang sein. Ein gutes Passwort wäre zum Beispiel ein Satz aus zufälligen Worten.
                        </p>

                        <h2>Wie gehen Angreifer vor?</h2>
                        <p>Wenn ein Computer oder ein Angreifer versucht dein Passwort zu knacken, hat er drei wesentliche Verfahren.</p>
                        <p>
                            Als erstes kann er dich Fragen. Dieses Verfahren nennt sich "Social Engineering" und passiert nicht immer offensichtlich.
                            Beliebt sind zum Beispiel E-Mails mit der Aufforderung ein Passwort zurückzusetzen oder das Passwort an einen "Support-Mitarbeiter" zu geben.
                            Im Maker Space solltest du niemals jemanden ein Passwort sagen. Auch nicht den Mitarbeitenden der Experimenta!
                            Mehr dazu findest du hier
                            <a href="https://de.wikipedia.org/wiki/Social_Engineering_%28Sicherheit%29">Wikipedia "Social Engineering</a>
                        </p>
                        <p>
                            Variante 2 sind sogenannte "Wörterbuchangriffe". Dabei versucht ein Angreifer es mit bekannten Passwörtern aus vorherigen Angriffen.
                            Diese Listen sind sehr groß und enthalten die häufigsten Passwörter. Diese Passwörter werden vom Computer automatisiert ausprobiert.
                            Auch hier kannst du auf Wikipedia mehr erfahren
                            <a href="https://de.wikipedia.org/wiki/W%C3%B6rterbuchangriff">Wikipedia "Wörterbuchangriff"</a>
                        </p>
                        <p>
                            Die dritte und aufwändigste Variante sind Bruteforce-Angriffe. Dabei versucht ein Angreifer alle Kombinationen an zeichen durch bis er einen Treffer erhält.
                            Gegen diesen Angriff hilft ein sehr langes Passwort sehr gut. Eine genauere Erklärung dazu findest du hier
                            <a href="https://de.wikipedia.org/wiki/Brute-Force-Methode">Wikipedia "Brute Force"</a>
                        </p>

                        <h2>Was ist ein Passwortmanager?</h2>
                        <p>Lange Passwörter sind schwer zu merken. Wir empfehlen daher die Verwendung eines Passwort-Managers.</p>
                        <p>
                            Ein Passwortmanager speichert alle deine Passwörter in einem verschlüsseltem Container.
                            Dieser Container ist für einen Angreifer nicht zugänglich und selbst wenn er ihn irgendwie bekommen sollte ist er immer noch verschlüsselt.
                        </p>

                        <img src="https://imgs.xkcd.com/comics/password_strength.png" />
                    </div>
                </div>



            </div>

        </div>
</div>

</form>
