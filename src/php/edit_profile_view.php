<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    session_start();
    if(!$_SESSION['ID'])
    	exit;

    require_once("bdd.php");
    $link = mysqli_connect(HOST, USER, PWD, BASE);
    mysqli_query($link, "SET NAMES UTF8");

    $query = $link->prepare("SELECT * FROM user WHERE ID = ?");
    $query->bind_param("i", $_SESSION['ID']);
    $query->execute();
    $user = $query->get_result()->fetch_assoc();

	$sql = "SELECT * FROM towns";
    $query = mysqli_query($link,$sql);
    while ( $results[] = mysqli_fetch_object ( $query ) );
    array_pop ( $results );
?>
    <div class="input__container">
        <select required placeholder="Votre ville" class="select -transparent register__town" name="town" id="town">
            <option value="" disabled hidden>Choisissez votre ville</option>
            <?php foreach ( $results as $option ) : ?>
            	<?php
            	$selected = "";
            	if((int)$option->ID === (int)$user['TOWN_ID'])
            		$selected = "selected";
            	?>
                <option value="<?php echo $option->ID; ?>" <?= $selected ?>><?php echo $option->NAME; ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <?php
    	$bio = "";
    	if($user['BIO'])
    		$bio = $user['BIO'];
    ?>
        <div class='information_group'><p class='information_title'>Biographie</p><textarea class='information_space' placeholder='Entrez une biographie...'><?= $bio ?></textarea></div>

        <div class='information_group'><p class='information_title'>Type de balade</p>
        <select>
    <?php
    	$types = array("Récréative", "Sportive", "Découverte");
    	$walk = 0;

  		foreach ($types as $type) {
  			$selected = "";
  			if($type == $user['WALK'])
  				$selected = "selected";
  			?> <option value="<?= $type ?>" <?= $selected ?>><?= $type ?></option> <?php
  		}
    ?>
    	</select>
	</div>
	<input type='button' value='Enregistrer' />