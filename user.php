<?php

require_once("app/bootstrap.php");

use Ladecadanse\HtmlShrink;
use Ladecadanse\Utils\Utils;
use Ladecadanse\Utils\Validateur;
use Ladecadanse\Utils\Text;

if (!$videur->checkGroup(12))
{
	header("Location: /user-login.php"); die();
}

$page_titre = "profil";
$page_description = "profil";
include("_header.inc.php");


$tab_elements = array("evenement" => "Événements", "lieu" => "Lieux", 'organisateur' => 'Organisateurs',
    "description" => "Descriptions", "commentaire" => "Commentaires");

$tab_type_elements = array("ajouts" => "ajoutés",  "favoris" => "Favoris", "participations" => "Participations");

if (isset($_GET['idP']))
{
	$get['idP'] = (int)$_GET['idP'];
}
else
{
	HtmlShrink::msgErreur("id obligatoire");
	exit;
}

if (isset($_GET['type_elements']))
{

	if (array_key_exists($_GET['type_elements'], $tab_type_elements))
	{
		$get['type_elements'] = $_GET['type_elements'];
	}
	else
	{
		HtmlShrink::msgErreur("type_elements faux");
		exit;
	}
}
else
{
		$get['type_elements'] = "ajouts";
}


if (isset($_GET['elements']))
{

	if (array_key_exists($_GET['elements'], $tab_elements))
	{
		$get['elements'] = $_GET['elements'];
	}
	else
	{
		HtmlShrink::msgErreur("element faux");
		exit;
	}
}
else
{
	if ((isset($_SESSION['Sgroupe']) && $_SESSION['Sgroupe'] <= 10))
	{

		$get['elements'] = "evenement";
	}
	else
	{
		if ($get['type_elements'] == "ajouts")
		{
			$get['elements'] = "commentaire";
		}
		else
		{
			$get['elements'] = "evenement";
		}

	}
}

if (isset($_GET['page']))
{
	$get['page'] = (int)$_GET['page'];
}
else
{
	$get['page'] = 1;
}

$tab_tri = array("dateAjout", "idOrganisateur", "idEvenement", "idLieu", "dateEvenement", "date_derniere_modif", "statut",
    "date_debut", "date_fin", "id", "titre", "nom", "prenom", "groupe", "pseudo");


if (isset($_GET['tri']))
{
	$get['tri'] = Validateur::validateUrlQueryValue($_GET['tri'], "enum", 1, $tab_tri);
}
else
{
	$get['tri'] = "dateAjout";
}


$tab_ordre = array("asc", "desc");
if (isset($_GET['ordre']))
{
	$get['ordre'] = Validateur::validateUrlQueryValue($_GET['ordre'], "enum", 1, $tab_ordre);

	if ($get['ordre'] == "asc")
	{
		$ordre_inverse = "desc";
	}
	else if ($get['ordre'] == "desc")
	{
		$ordre_inverse = "asc";
	}
}
else
{
	$get['ordre'] = "desc";
	$ordre_inverse = "asc";
}

$get['nblignes'] = 100;
if (!empty($_GET['nblignes']))
{
	$get['nblignes'] = Validateur::validateUrlQueryValue($_GET['nblignes'], "int", 1);
}

if ($_SESSION['SidPersonne'] != $get['idP'] && $_SESSION['Sgroupe'] > 4 )
{
	HtmlShrink::msgErreur("Vous ne pouvez accédez à cette page");
	exit;
}

//details de la personne
$req_personne = $connector->query("SELECT idPersonne, pseudo, nom, prenom, affiliation,
 adresse, email, telephone, URL, groupe, actif FROM personne WHERE idPersonne=".$get['idP']);

$detailsPersonne = $connector->fetchArray($req_personne);

//jointure pour obtenir toutes les affiliations qui sont liées à cette personne
$req_affPers = $connector->query("SELECT lieu.idLieu, lieu.nom
FROM affiliation INNER JOIN lieu ON affiliation.idAffiliation=lieu.idLieu
 WHERE affiliation.idPersonne=".$get['idP']." AND affiliation.genre='lieu'");

$detailsAff = $connector->fetchArray($req_affPers);


?>



<!-- Deb Contenu -->
<div id="contenu" class="colonne personne">

	<div id="entete_contenu">
		<h2>Profil</h2>
		<div class="spacer"></div>
	</div>

	<div class="spacer"></div>

	<!-- Deb profile -->
	<div id="profile" style="padding: 0.4em;width: 94%;margin: 0 auto 0 auto;">

		<table>
            <tr><th>Identifiant</th><td><?php echo sanitizeForHtml($detailsPersonne['pseudo']) ?></td></tr>
            <tr><th>E-mail</th><td><?php echo sanitizeForHtml($detailsPersonne['email']) ?></td></tr>
            <tr><th>Affiliations</th><td>
            <?php
            //si l'affiliation est un lieu, crée un lien vers ce lieu
            if ($connector->getNumRows($req_affPers) > 0)
            {
                echo "<a href=\"/lieu.php?idL=".sanitizeForHtml($detailsAff['idLieu'])."\" title=\"Voir la fiche du lieu : ".sanitizeForHtml($detailsAff['idLieu'])."\" >".sanitizeForHtml($detailsAff['nom'])."</a>";
            }
            else
            {
                echo sanitizeForHtml($detailsPersonne['affiliation']);
            }
            echo '<br />';
            $sql = "SELECT * FROM personne_organisateur, organisateur WHERE personne_organisateur.idOrganisateur=organisateur.idOrganisateur AND personne_organisateur.idPersonne=".$get['idP'];
            $req = $connector->query($sql);
            while ($tab = $connector->fetchArray($req))
            {
                echo '<a href="/organisateur.php?idO='.$tab['idOrganisateur'].'">'.$tab['nom'].'</a><br />';
            }
            ?>

                </td></tr>
        </table>
        <?php if ((isset($_SESSION['Sgroupe']) && ($_SESSION['Sgroupe'] <= 1)) || $_SESSION['SidPersonne'] == $get['idP']) { ?>
        <a href="/user-edit.php?idP=<?php echo $get['idP'] ?>&action=editer"><img src="/web/interface/icons/user_edit.png" alt="" />Modifier</a><?php } ?>
    </div> <!-- Fin profile -->


	<ul id="menu_principal">
        <li <?php if ($get['type_elements'] == "ajouts") { echo ' class="ici" '; } ?>>
        <a href="/user.php?idP=<?php echo $get['idP'] ?>&type_elements=ajouts">
        <?php echo $icone['ajouts']."Éléments ajoutés" ?></a></li>
        <li <?php if ($get['type_elements'] == "favoris") { echo ' class="ici" '; } ?>>
        <a href="/user.php?idP=<?php echo $get['idP'] ?>&type_elements=favoris">
            <?php echo "Favoris" ?></a></li>
    </ul>

<?php
if ($get['type_elements'] == 'favoris')
{
?>
    <ul id="menu_ajouts">
            <li <?php if ($get['elements'] == "evenement") { echo ' class="ici" '; } ?>>
		<img src="/web/interface/icons/calendar.png" />
		<a href="<?php echo $_SERVER['PHP_SELF']."?idP=".$get['idP']."&type_elements=".$get['type_elements']."&elements=evenement" ?>">événements</a>
        </li>
        </ul>

	<div class="spacer"></div>
	<?php

if ($get['elements'] == "evenement")
{
	$sql_favoris = "SELECT *
	 FROM evenement, evenement_favori WHERE evenement_favori.idPersonne=".$get['idP']."
	 AND evenement_favori.idEvenement=evenement.idEvenement
	 ORDER BY dateAjout LIMIT ".($get['page'] - 1) * $get['nblignes'].",".$get['nblignes'];


	$req_favoris = $connector->query($sql_favoris);

	$req_nb_fav = $connector->query("SELECT COUNT(*) AS nbeven FROM evenement_favori WHERE idPersonne=".$get['idP']);
	$tab_nb_fav = $connector->fetchArray($req_nb_fav);
	$tot_fav = $tab_nb_fav['nbeven'];

	echo HtmlShrink::getPaginationString($get['page'], $tot_fav, $get['nblignes'], 1, $_SERVER['PHP_SELF'], "?idP=".$get['idP']."&elements=".$get['elements']."&type_elements=".$get['type_elements']."&page=");


	if ($connector->getNumRows($req_favoris) > 0)
	{

		$th_evenements = array("dateEvenement" => "Date", "titre" => "Titre", "idLieu" => "Lieu",
		"flyer" => "Flyer");


		echo "<table id=\"favoris_evenements\"><tr>";


		echo '<th colspan="2">Événement</th><th>Lieu</th><th>Date</th></tr>';

            $pair = 0;

		while ($tab_even = $connector->fetchArray($req_favoris))
		{

			$nomLieu = sanitizeForHtml($tab_even['nomLieu']);

			if ($tab_even['idLieu'] != 0)
			{
				$req_lieu = $connector->query("SELECT nom FROM lieu WHERE idLieu=".$tab_even['idLieu']);
				$tabLieu = $connector->fetchArray($req_lieu);
				$nomLieu = "<a href=\"/lieu.php?idL=".$tab_even['idLieu']."\" title=\"Voir la fiche du lieu : ".sanitizeForHtml($tabLieu['nom'])." \">".sanitizeForHtml($tabLieu['nom'])."</a>";
			}


			if ($pair % 2 == 0)
			{
				echo "<tr>";
			}
			else
			{
				echo "<tr class=\"impair\" >";
			}

			echo '
			<td class="flyer">';
			if (!empty($tab_even['flyer']))
			{
				$imgInfo = @getimagesize($rep_images_even.$tab_even['flyer']);
				echo HtmlShrink::popupLink($url_uploads_events.$tab_even['flyer']."?".@filemtime($rep_images_even.$tab_even['flyer']),
				"Flyer", $imgInfo[0]+20,$imgInfo[1]+20,
				'<img src="'.$url_uploads_events.'t_'.$tab_even['flyer'].'" alt="Flyer" width="60" />'
				);
			}
			echo '</td>';

			echo '<td>';
			echo "<h3><a href=\"/evenement.php?idE=".$tab_even['idEvenement']."\"
			title=\"Voir la fiche de l'événement\">".sanitizeForHtml($tab_even['titre'])."</a></h3>";

			echo '<p class="description">';

			if (!empty($tab_even['description']))
			{
				$maxChar = Text::trouveMaxChar($tab_even['description'], 45, 2);
				echo @Text::texteHtmlReduit(Text::wikiToHtml($tab_even['description']),
				$maxChar,
				"<span class=\"continuer\"><a href=\"/evenement.php?idE=".$tab_even['idEvenement']."\"
				title=\"Voir la fiche complète de l'événement\"> Lire la suite</a></span>");
			}

			echo '</p>';
			?>
<p class="pratique"><?php echo afficher_debut_fin($tab_even['horaire_debut'], $tab_even['horaire_fin'], $tab_even['dateEvenement'])." ".$tab_even['prix'] ?></p>
			<?php
			echo "</td>";

			echo "<td>".$nomLieu."</td>";

			echo "
			<td>".date_iso2app($tab_even['dateEvenement'])."</td>";

			if ($_SESSION['Sgroupe'] <= 4)
			{
				echo "<td><a href=\"/evenement-edit.php?action=editer&idE=".$tab_even['idEvenement']."\" title=\"Éditer l'événement\">".$iconeEditer."</a></td>";
			}
			else
			{
				echo "<td><!-- --></td>";
			}

                echo "</tr>";

			$pair++;
		} // fin while

		echo "</table>";

	}
	else
	{
		echo '<p>Aucun '.$get['elements'].' en favori pour le moment</p>';
	}//if nbrows evenements


    }
}
else
{
?>

	<ul id="menu_ajouts">

	<?php
	/*			foreach ($tab_elements as $titre => $elem)
				{
					echo "<li";

					if ($titre == $get['elements'])
					{
						echo " class=\"ici\" style=\"background:white\"";
					}
					echo ">
					<a href=\"".$_SERVER['PHP_SELF']."?idP=".$get['idP']."&elements=".$titre."&nblignes=".$get['nblignes']."\">".$elem."</a></li>";
				}*/
	?>


	<?php
	if ((isset($_SESSION['Sgroupe']) && $_SESSION['Sgroupe'] <= 10))
	{
	?>


	<li <?php if ($get['elements'] == "evenement") { echo ' class="ici" '; } ?>>
	<img src="/web/interface/icons/calendar.png" />
	<a href="<?php echo $_SERVER['PHP_SELF']."?idP=".$get['idP']."&nblignes=".$get['nblignes']."&elements=evenement" ?>">événements</a>
	</li>
	<?php
	}

        if ((isset($_SESSION['Sgroupe']) && $_SESSION['Sgroupe'] <= 6))
	{
	?>
	<li <?php if ($get['elements'] == "lieu") { echo ' class="ici" '; } ?>>
	<img src="/web/interface/icons/building.png" />
	<a href="<?php echo $_SERVER['PHP_SELF']."?idP=".$get['idP']."&nblignes=".$get['nblignes']."&elements=lieu" ?>">lieux</a>
	</li>
	<?php
	}

	if ((isset($_SESSION['Sgroupe']) && $_SESSION['Sgroupe'] <= 6))
	{
	?>
	<li <?php if ($get['elements'] == "organisateur") { echo ' class="ici" '; } ?>>
	<img src="/web/interface/icons/group.png" />
	<a href="<?php echo $_SERVER['PHP_SELF']."?idP=".$get['idP']."&nblignes=".$get['nblignes']."&elements=organisateur" ?>">organisateurs</a>
	</li>
	<?php
	}

	if ((isset($_SESSION['Sgroupe']) && $_SESSION['Sgroupe'] <= 8))
	{
	?>
	<li <?php if ($get['elements'] == "description") { echo ' class="ici" '; } ?>>
	<img src="/web/interface/icons/page_white_text.png" />
	<a href="<?php echo $_SERVER['PHP_SELF']."?idP=".$get['idP']."&nblignes=".$get['nblignes']."&elements=description" ?>">textes</a>
	</li>
	<?php
	}
	?>

	<li <?php if ($get['elements'] == "commentaire") { echo ' class="ici" '; } ?>>
	<img src="/web/interface/icons/comment.png" />
	<a href="<?php echo $_SERVER['PHP_SELF']."?idP=".$get['idP']."&nblignes=".$get['nblignes']."&elements=commentaire" ?>">commentaires</a>
	</li>

	</ul>


	<?php
	// EVENEMENTS ANNONCES

	$limite = 20;

	if ($get['elements'] == "evenement")
	{
		$sql_evenement = "SELECT idEvenement, idLieu, statut, idPersonne, genre,titre, dateEvenement, nomLieu, flyer, dateAjout
		 FROM evenement WHERE idPersonne=".$get['idP']." ORDER BY ".$get['tri']." ".$get['ordre']." LIMIT ".($get['page'] - 1) * $get['nblignes'].",".$get['nblignes'];


		$req_evenement = $connector->query($sql_evenement);

		$req_nbeven = $connector->query("SELECT COUNT(*) AS nbeven FROM evenement WHERE idPersonne=".$get['idP']);
		$tab_nbeven = $connector->fetchArray($req_nbeven);
		$tot_elements = $tab_nbeven['nbeven'];

		echo HtmlShrink::getPaginationString($get['page'], $tot_elements, $get['nblignes'], 1, $_SERVER['PHP_SELF'], "?idP=".$get['idP']."&elements=".$get['elements']."&tri=".$get['tri']."&ordre=".$get['ordre']."&nblignes=".$get['nblignes']."&page=");


		if ($connector->getNumRows($req_evenement) > 0)
		{
			echo '<ul id="menu_nb_res">';
			foreach ($tab_nblignes as $nbl)
			{
				echo '<li ';
				if ($get['nblignes'] == $nbl) { echo 'class="ici"'; }

				echo '><a href="'.$_SERVER['PHP_SELF'].'?'.Utils::urlQueryArrayToString($get, "nblignes").'&nblignes='.$nbl.'">'.$nbl.'</a></li>';
			}
			echo '</ul>';
			echo '<div class="spacer"><!-- --></div>';
			$th_evenements = array("dateEvenement" => "Date", "idLieu" => "Lieu", "titre" => "Titre", "dateAjout" => "Date d'ajout", "statut" => "");


			echo "<table id=\"ajouts\"><tr>";

			foreach ($th_evenements as $att => $th)
			{
				if ($att == "idLieu" || $att == "flyer")
				{
					echo "<th>".$th."</th>";
				}
				else
				{
					if ($att == $get['tri'])
					{
						echo "<th class=\"ici\">".$icone[$get['ordre']];
					}
					else
					{
						echo "<th>";
					}

					echo "<a href=\"".$_SERVER['PHP_SELF']."?idP=".$get['idP']."&elements=".$get['elements']."&page=".$get['page']."&tri=".$att."&ordre=".$ordre_inverse."&nblignes=".$get['nblignes']."\">".$th."</a></th>";
				}
			}

			echo "<th></th></tr>";

			$pair = 0;

			while ($tab_even = $connector->fetchArray($req_evenement))
			{

				$nomLieu = sanitizeForHtml($tab_even['nomLieu']);

				if ($tab_even['idLieu'] != 0)
				{
					$req_lieu = $connector->query("SELECT nom FROM lieu WHERE idLieu=".$tab_even['idLieu']);
					$tabLieu = $connector->fetchArray($req_lieu);
					$nomLieu = "<a href=\"/lieu.php?idL=".$tab_even['idLieu']."\" title=\"Voir la fiche du lieu : ".sanitizeForHtml($tabLieu['nom'])." \">".sanitizeForHtml($tabLieu['nom'])."</a>";
				}


				if ($pair % 2 == 0)
				{
					echo "<tr>";
				}
				else
				{
					echo "<tr class=\"impair\" >";
				}

				echo "
				<td>".date_iso2app($tab_even['dateEvenement'])."</td>
				<td>".$nomLieu."</td>
				<td><a href=\"/evenement.php?idE=".$tab_even['idEvenement']."\" title=\"Voir la fiche de l'événement\">".sanitizeForHtml($tab_even['titre'])."</a></td>";
				/*echo "<td>";
				if (!empty($tab_even['flyer']))
				{
					$imgInfo = @getimagesize($rep_images_even.$tab_even['flyer']);
					echo lien_popup($IMGeven.$tab_even['flyer']."?".@filemtime($rep_images_even.$tab_even['flyer']), "Flyer", $imgInfo[0]+20,$imgInfo[1]+20, $iconeImage);
				}
				echo "</td>
				*/
				echo "
				<td>".mb_substr(date_iso2app($tab_even['dateAjout']), 9)."</td><td>".$tab_icones_statut[$tab_even['statut']]."</td>";

				if ($_SESSION['SidPersonne'] == $detailsPersonne['idPersonne'] || $_SESSION['Sgroupe'] <= 4)
				{
					echo "<td><a href=\"/evenement-edit.php?action=editer&idE=".$tab_even['idEvenement']."\" title=\"Éditer l'événement\">".$iconeEditer."</a></td>";
				}
				echo "</tr>";

				$pair++;
			} // fin while

			echo "</table>";

		}
		else
		{
			echo '<p>Aucun '.$get['elements'].' ajouté pour le moment</p>';
		}//if nbrows evenements

		@mysqli_free_result($req_evenement);

	}
	else if ($get['elements'] == "description")
	{

		// DESCRIPTIONS DE LIEUX

		$req_des = $connector->query("SELECT idLieu, idPersonne, dateAjout, contenu, type
		FROM descriptionlieu WHERE idPersonne=".$get['idP']. " ORDER BY ".$get['tri']." ".$get['ordre']." LIMIT ".($get['page'] - 1) * $get['nblignes'].",".$get['nblignes']);

		$req_nbdesc = $connector->query("SELECT COUNT(*) AS total FROM descriptionlieu WHERE idPersonne=".$get['idP']);
		$tab_nbdesc = $connector->fetchArray($req_nbdesc);
		$tot_elements = $tab_nbdesc['total'];



		echo HtmlShrink::getPaginationString($get['page'], $tot_elements, $get['nblignes'], 1, $_SERVER['PHP_SELF'], "?idP=".$get['idP']."&elements=".$get['elements']."&tri=".$get['tri']."&ordre=".$get['ordre']."&nblignes=".$get['nblignes']."&page=");

		if ($connector->getNumRows($req_des) > 0)
		{
			echo '<ul id="menu_nb_res">';
			foreach ($tab_nblignes as $nbl)
			{
				echo '<li ';
				if ($get['nblignes'] == $nbl) { echo 'class="ici"'; }

				echo '><a href="'.$_SERVER['PHP_SELF'].'?'.Utils::urlQueryArrayToString($get, "nblignes").'&nblignes='.$nbl.'">'.$nbl.'</a></li>';
			}
			echo '</ul>';
			echo '<div class="spacer"><!-- --></div>';

			$th_descriptions = array("idLieu" => "Lieu",  "contenu" => "Contenu", "type" => "Type", "dateAjout" => "Date d'ajout");

			echo "<table id=\"ajouts\"><tr>";
			foreach ($th_descriptions as $att => $th)
			{
				if ($att == "idLieu" || $att == "idPersonne" || $att == "contenu")
				{
					echo "<th>".$th."</th>";
				}
				else
				{
					if ($att == $get['tri'])
					{
						echo "<th class=\"ici\">".$icone[$get['ordre']];
					}
					else
					{
						echo "<th>";
					}

					echo "<a href=\"".$_SERVER['PHP_SELF']."?idP=".$get['idP']."&elements=".$get['elements']."&page=".$get['page']."&tri=".$att."&ordre=".$ordre_inverse."&nblignes=".$get['nblignes']."\">".$th."</a></th>";
				}
			}
			echo "<th></th></tr>";

			$pair = 0;

			while($tab_desc = $connector->fetchArray($req_des))
			{

				$req_auteur = $connector->query("SELECT pseudo FROM personne WHERE idPersonne=".$tab_desc['idPersonne']);
				$tabAuteur = $connector->fetchArray($req_auteur);

				$req_lieu = $connector->query("SELECT nom FROM lieu WHERE idLieu=".$tab_desc['idLieu']);
				$tabLieu = $connector->fetchArray($req_lieu);
				$nomLieu = "<a href=\"/lieu.php?idL=".$tab_desc['idLieu']."\" title=\"Éditer le lieu\">".sanitizeForHtml($tabLieu['nom'])."</a>";


				if ($pair % 2 == 0)
				{
					echo "<tr>";
				}
				else
				{
					echo "<tr class=\"impair\" >";
				}

				echo "<td>".$nomLieu."</td>";
				if (mb_strlen($tab_desc['contenu']) > 200)
				{
					$tab_desc['contenu'] = mb_substr($tab_desc['contenu'], 0, 200)." [...]";
				}

				echo "<td class=\"tdleft\" style=\"width:150px\">".Text::wikiToHtml(sanitizeForHtml($tab_desc['contenu']))."</td>";
				echo '<td>'.$tab_desc['type'].'</td>';
				echo "<td>".mb_substr(date_iso2app($tab_desc['dateAjout']), 8)."</td>";
				if ($_SESSION['SidPersonne'] == $detailsPersonne['idPersonne'] || $_SESSION['Sgroupe'] <= 4)
				{
					echo "<td><a href=\"/lieu-text-edit.php?action=editer&idL=" . $tab_desc['idLieu'] . "&idP=" . $tab_desc['idPersonne'] . "&type=" . $tab_desc['type'] . "\" title=\"Éditer le lieu\">" . $iconeEditer . "</a></td>";
                }
				echo "</tr>";

				$pair++;
			} // while

			echo "</table>";
		}
		else
		{
			echo '<p>Aucun texte ajouté pour le moment</p>';
		}
		 //if nbrow descriptions

		@mysqli_free_result($req_des);


	}
	else if ($get['elements'] == "lieu")
	{
	// LIEUX

	$req_lieux = $connector->query("SELECT idLieu, idPersonne, nom, quartier,
	 categorie, URL, dateAjout FROM lieu
	 WHERE idPersonne=".$get['idP']." ORDER BY ".$get['tri']." ".$get['ordre']." LIMIT ".($get['page'] - 1) * $get['nblignes'].",".$get['nblignes']);

	$req_count = $connector->query("SELECT COUNT(*) AS total FROM lieu WHERE idPersonne=".$get['idP']);
	$tab_count = $connector->fetchArray($req_count);
	$tot_elements = $tab_count['total'];


	echo HtmlShrink::getPaginationString($get['page'], $tot_elements, $get['nblignes'], 1, $_SERVER['PHP_SELF'], "?idP=".$get['idP']."&elements=".$get['elements']."&tri=".$get['tri']."&ordre=".$get['ordre']."&nblignes=".$get['nblignes']."&page=");


	if ($connector->getNumRows($req_lieux) > 0)
	{

		$th_lieu = array("idLieu" => "ID",  "nom" => "Nom", "categorie" => "Catégorie", "URL" => "Site web", "description" => "Desc", "dateAjout" => "Date d'ajout");

		echo '<ul id="menu_nb_res">';
		foreach ($tab_nblignes as $nbl)
		{
			echo '<li ';
			if ($get['nblignes'] == $nbl) { echo 'class="ici"'; }

			echo '><a href="'.$_SERVER['PHP_SELF'].'?'.Utils::urlQueryArrayToString($get, "nblignes").'&nblignes='.$nbl.'">'.$nbl.'</a></li>';
		}
		echo '</ul>';
			echo '<div class="spacer"><!-- --></div>';
		echo "
		<table id=\"ajouts\">
		<tr>";

		foreach ($th_lieu as $att => $th)
		{
			if ($att == "adresse" || $att == "categorie" || $att == "URL" || $att == "description")
			{
				echo "<th>".$th."</th>";
			}
			else
			{
				if ($att == $get['tri'])
				{
					echo "<th class=\"ici\">".$icone[$get['ordre']];
				}
				else
				{
					echo "<th>";
				}

				echo "<a href=\"".$_SERVER['PHP_SELF']."?idP=".$get['idP']."&elements=".$get['elements']."&page=".$get['page']."&tri=".$att."&ordre=".$ordre_inverse."&nblignes=".$get['nblignes']."\">".$th."</a></th>";
			}
		}

		echo "<th></th></tr>";

		$pair = 0;

		while(list($idLieu, $idPersonne, $nom, $quartier, $categorie, $URL, $dateAjout) = $connector->fetchArray($req_lieux))
		{

			$req_nbDes = $connector->query("SELECT COUNT(*) FROM descriptionlieu WHERE idLieu=".$idLieu);
			$tabDes = $connector->fetchArray($req_nbDes);

			$listeCat = explode(",", $categorie);

			if ($pair % 2 == 0)
			{
				echo "<tr>";
			}
			else
			{
				echo "<tr class=\"impair\" >";
			}

			echo "
			<td>".$idLieu."</td>
			<td><a href=\"/lieu.php?idL=".$idLieu."\" title=\"Voir la fiche du lieu :".sanitizeForHtml($nom)."\">".sanitizeForHtml($nom)."</a></td>
			<td class=\"tdleft\"><ul>";


			for ($i=0, $totalCat = count($listeCat); $i<$totalCat; $i++){
				echo "<li>".$listeCat[$i]."</li>";
			}

			echo "</ul></td>";
			echo "<td>";
			if (!empty($URL)) {
				echo "<a href=\"http://".$URL."\" title=\"Aller sur le site du lieu\">".$iconeURL."</a>\n";
			}
			echo "</td>";
			echo "
			<td>".$tabDes['COUNT(*)']."</td>
			<td>".date_iso2app($dateAjout)."</td>";
			//Edition pour l'admin ou l'auteur
			if ($_SESSION['SidPersonne'] == $detailsPersonne['idPersonne'] || $_SESSION['Sgroupe'] <= 4)
			{
				echo "<td><a href=\"/lieu-edit?action=editer&idL=".$idLieu."\" title=\"Éditer le lieu\">".$iconeEditer."</a></td>";
			}
			echo "</tr>";

			$pair++;

		}

		echo "</table>";

	}
	}
else if ($get['elements'] == "organisateur")
{

	if ($get['tri'] == 'dateAjout')
	{
		$get['tri'] = 'date_ajout';
	}

	$req_lieux = $connector->query("SELECT * FROM organisateur
	 WHERE idPersonne=".$get['idP']." ORDER BY ".$get['tri']." ".$get['ordre']." LIMIT ".($get['page'] - 1) * $get['nblignes'].",".$get['nblignes']);

	$req_count = $connector->query("SELECT COUNT(*) AS total FROM organisateur WHERE idPersonne=".$get['idP']);
	$tab_count = $connector->fetchArray($req_count);
	$tot_elements = $tab_count['total'];

	echo HtmlShrink::getPaginationString($get['page'], $tot_elements, $get['nblignes'], 1, $_SERVER['PHP_SELF'], "?idP=".$get['idP']."&elements=".$get['elements']."&tri=".$get['tri']."&ordre=".$get['ordre']."&nblignes=".$get['nblignes']."&page=");

	if ($connector->getNumRows($req_lieux) > 0)
	{

		$th_lieu = array("idOrganisateur" => "ID",  "nom" => "Nom", "URL" => "Site web", "dateAjout" => "Date d'ajout");

		echo '<ul id="menu_nb_res">';
		foreach ($tab_nblignes as $nbl)
		{
			echo '<li ';
			if ($get['nblignes'] == $nbl) { echo 'class="ici"'; }

			echo '><a href="'.$_SERVER['PHP_SELF'].'?'.Utils::urlQueryArrayToString($get, "nblignes").'&nblignes='.$nbl.'">'.$nbl.'</a></li>';
		}
		echo '</ul>';
		echo '<div class="spacer"><!-- --></div>';
		echo "<table id=\"ajouts\">
		<tr>";

		foreach ($th_lieu as $att => $th)
		{
			if ($att == "URL" || $att == "presentation")
			{
				echo "<th>".$th."</th>";
			}
			else
			{
				if ($att == $get['tri'])
				{
					echo "<th class=\"ici\">".$icone[$get['ordre']];
				}
				else
				{
					echo "<th>";
				}

				echo "<a href=\"".$_SERVER['PHP_SELF']."?idP=".$get['idP']."&elements=".$get['elements']."&page=".$get['page']."&tri=".$att."&ordre=".$ordre_inverse."&nblignes=".$get['nblignes']."\">".$th."</a></th>";
			}
		}

		echo "<th></th></tr>";

		$pair = 0;

		while ($tab = $connector->fetchArray($req_lieux))
		{

			if ($pair % 2 == 0)
			{
				echo "<tr>";
			}
			else
			{
				echo "<tr class=\"impair\" >";
			}

			echo "
			<td>".$tab['idOrganisateur']."</td>
			<td><a href=\"/organisateur.php?idO=".$tab['idOrganisateur']."\" title=\"Voir la fiche\">".sanitizeForHtml($tab['nom'])."</a></td>
	</td>";
			echo "<td>";
			if (!empty($tab['URL'])) {
				echo "<a href=\"http://".$tab['URL']."\" title=\"Aller sur le site du lieu\">".$iconeURL."</a>\n";
			}
			echo "</td>";
			echo "
			<td>".date_iso2app($tab['date_ajout'])."</td>";
			//Edition pour l'admin ou l'auteur
			if ($_SESSION['SidPersonne'] == $detailsPersonne['idPersonne'] || $_SESSION['Sgroupe'] <= 4)
			{
				echo "<td><a href=\"/organisateur-edit.php?action=editer&idO=".$tab['idOrganisateur']."\" title=\"Éditer le lieu\">".$iconeEditer."</a></td>";
			}
			echo "</tr>";

			$pair++;

		}

		echo "</table>";

	}
	else
	{
		echo '<p>Aucun '.$get['elements'].' ajouté pour le moment</p>';
	}

}
    else if ($get['elements'] == "commentaire")
{



$req_comm = $connector->query("SELECT idCommentaire, id, element, contenu, statut, dateAjout
FROM commentaire WHERE idPersonne=".$get['idP']." ORDER BY ".$get['tri']." ".$get['ordre']." LIMIT ".($get['page'] - 1) * $get['nblignes'].",".$get['nblignes']);

$req_count = $connector->query("SELECT COUNT(*) AS total FROM commentaire WHERE idPersonne=".$get['idP']);
$tab_count = $connector->fetchArray($req_count);
$tot_elements = $tab_count['total'];

echo HtmlShrink::getPaginationString($get['page'], $tot_elements, $get['nblignes'], 1, $_SERVER['PHP_SELF'], "?idP=".$get['idP']."&elements=".$get['elements']."&nblignes=".$get['nblignes']."&page=");



if ($connector->getNumRows($req_comm) > 0)
{

	echo '<ul id="menu_nb_res">';
	foreach ($tab_nblignes as $nbl)
	{
		echo '<li ';
		if ($get['nblignes'] == $nbl) { echo 'class="ici"'; }

		echo '><a href="'.$_SERVER['PHP_SELF'].'?'.Utils::urlQueryArrayToString($get, "nblignes").'&nblignes='.$nbl.'">'.$nbl.'</a></li>';
	}
	echo '</ul>';
	echo '<div class="spacer"><!-- --></div>';

	$th_comm = array("contenu" => "Commentaire", "id" => "Nom", "element" => "Élément", "dateAjout" => "Date d'ajout", "statut" => "" );

	echo "<table id=\"ajouts\"><tr>";

	foreach ($th_comm as $att => $th)
	{
		if ($att == "contenu" || $att == "heure")
		{
			echo "<th>".$th."</th>";
		}
		else
		{
			if ($att == $get['tri'])
			{
				echo "<th class=\"ici\">".$icone[$get['ordre']];
			}
			else
			{
				echo "<th>";
			}

			echo "<a href=\"".$_SERVER['PHP_SELF']."?idP=".$get['idP']."&elements=".$get['elements']."&page=".$get['page']."&tri=".$att."&ordre=".$ordre_inverse."&nblignes=".$get['nblignes']."\">".$th."</a></th>";
		}
	}

	echo "<th></th></tr>";
	$pair = 0;

	while($tab_comm = $connector->fetchArray($req_comm))
	{

		if ($pair % 2 == 0)
		{
			echo "<tr>";
		}
		else
		{
			echo "<tr class=\"impair\" >";
		}

		echo "<td class=\"tdleft\" style=\"width:150px\">".Text::wikiToHtml(sanitizeForHtml($tab_comm['contenu']))."</td>";

		if ($tab_comm['element'] == 'evenement')
		{

		$req_even = $connector->query("SELECT titre FROM evenement WHERE idEvenement=".$tab_comm['id']);
		$tab_even = $connector->fetchArray($req_even);
		echo "<td><a href=\"/evenement.php?idE=".$tab_comm['id']."\" title=\"Voir l'événement\">".$tab_even['titre']."</a></td>";

		}
		else if ($tab_comm['element'] == 'lieu')
		{
		$req_even = $connector->query("SELECT nom FROM lieu WHERE idLieu=".$tab_comm['id']);
		$tab_even = $connector->fetchArray($req_even);
		echo "<td><a href=\"/lieu.php?idL=".$tab_comm['id']."\" title=\"Voir le lieu\">".$tab_even['nom']."</a></td>";

		}

		echo "<td>".$tab_comm['element']."</td>";


		$tab_dateAjout = explode(" ", $tab_comm['dateAjout']);

		echo "<td>".date_iso2app($tab_dateAjout[0])."</td>";
		echo "<td>".$tab_icones_statut[$tab_comm['statut']]."</td>";

		//echo "<td>".$tab_dateAjout[1]."</td>";
		//Edition pour l'admin ou l'auteur
		if ($_SESSION['Sgroupe'] <= 4)
		{
			echo "<td><a href=\"/multi-comment.php?action=editer&idC=".$tab_comm['idCommentaire']."\" title=\"Éditer la brève\">".$iconeEditer."</a></td>";
		}

		echo "</tr>";

		$pair++;
	}

	echo "</table>";
}
else
{
	echo '<p>Aucun '.$get['elements'].' ajouté pour le moment</p>';
}
    }

echo HtmlShrink::getPaginationString($get['page'], $tot_elements, $get['nblignes'], 1, $_SERVER['PHP_SELF'], "?idP=".$get['idP']."&elements=".$get['elements']."&tri=".$get['tri']."&ordre=".$get['ordre']."&nblignes=".$get['nblignes']."&page=");

?>
	</table>


<?php

} // if element
?>

</div>
<!-- fin Contenu -->

<div id="colonne_gauche" class="colonne">
<?php
include("_navigation_calendrier.inc.php");
?>
</div>
<!-- Fin Colonnegauche -->


<div id="colonne_droite" class="colonne personne"></div>
<!-- Fin colonne_droite -->



<?php
include("_footer.inc.php");
?>
