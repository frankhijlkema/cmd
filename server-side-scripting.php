<?php

ini_set('display_errors', 'On'); 
error_reporting(E_ALL); 

require('../inc/config.inc.php');
require('../inc/template.inc.php');
require('../inc/functions.inc.php');

authenticeer();

$link = dbConnect();

if(isset($_GET['pagina']) && $_GET['pagina'] == "paginas")
{

  htmlOpenen('Pagina&lsquo;s');

	echo '
			<article>
				<h3>Overzicht</h3>
				<ul id="list">';

	$result = $link->query('SELECT * FROM '.$_GET['pagina'].'');
	while($record = $result->fetch_array())
	{
		echo '
					<li><a href="index.php?pagina='.$_GET['pagina'].'&actie=bewerken&id='.$record['id'].'">'.$record['titel'].'</a></li>';	
	}

	echo '
					<li><a href="index.php?pagina='.$_GET['pagina'].'" id="new">Pagina toevoegen</a></li>	
				</ul>
			</article>
			<article class="doubleSize">';

	if(isset($_GET['actie']) && $_GET['actie'] == "bewerken" && isset($_GET['id']))
	{
		$result = $link->query('SELECT * FROM paginas WHERE id='.$_GET['id'].';');
		$record = $result->fetch_array();

		echo '
				<h3>Pagina &lsquo;'.$record['titel'].'&rsquo; bewerken</h3>';


		if(isset($_POST['submit']))
		{	
			if (empty($_POST['titel']))
				echo '<p>De titel is verplicht. <a href="javascript:history.back()">Ga terug</a></p>';
			elseif (strlen($_POST['titel']) > 70)
				echo '<p>De titel mag maximaal 70 tekens lang zijn. <a href="javascript:history.back()">Ga terug</a></p>';
			elseif (empty($_POST['beschrijving']))
				echo '<p>De korte beschrijving is verplicht. <a href="javascript:history.back()">Ga terug</a></p>';
			elseif (strlen($_POST['beschrijving']) > 160)
				echo '<p>De korte beschrijving mag maximaal 160 tekens lang zijn. <a href="javascript:history.back()">Ga terug</a></p>';
			elseif (empty($_POST['content']))
				echo '<p>De inhoud is verplicht. <a href="javascript:history.back()">Ga terug</a></p>';
			else
			{
				$link->query("UPDATE paginas SET titel = '".strip_tags($_POST['titel'])."', beschrijving = '".strip_tags($_POST['beschrijving'])."', content = '".strip_tags($_POST['content'])."' WHERE id=".$_GET['id'].";");	
				echo '<p class="green">De pagina is gewijzigd&hellip;</p>';
				header('refresh: 2; url = "'.$_SERVER['REQUEST_URI'].'"');
			}
		}
		else
		{
			echo '
				<form action="'.$_SERVER['REQUEST_URI'].'" method="post">
					<label for="titel">Titel:</label>
					<input type="text" id="titel" name="titel" maxlength="70" value="'.$record['titel'].'" /><br />

					<label for="beschrijving">Korte beschrijving:</label>
					<input type="text" id="beschrijving" name="beschrijving" maxlength="160" value="'.$record['beschrijving'].'" /><br />

					<label for="content">Inhoud:</label>
					<textarea id="content" name="content" rows="10" cols="10">'.$record['content'].'</textarea><br />

					<input type="submit" name="submit" value="Wijzigingen opslaan" /> <a href="'.$_SERVER['PHP_SELF'].'?pagina=paginas&actie=verwijderen&id='.$_GET['id'].'" class="deleteButton">Pagina verwijderen</a>
				</form>
			</article>';
		}
	}
	elseif(isset($_GET['actie']) && $_GET['actie'] == "verwijderen" && isset($_GET['id']))
	{
			$link->query("DELETE FROM paginas WHERE id=".$_GET['id'].";");
			echo '
				<h3>Pagina verwijderen</h3>	
				<p class="green">De pagina is verwijderd.</p>';
	}
	else
	{

		echo '
				<h3>Pagina toevoegen</h3>';

		if(isset($_POST['submit']))
		{
			if (empty($_POST['titel']))
				echo '<p>De titel is verplicht. <a href="javascript:history.back()">Ga terug</a></p>';
			elseif (strlen($_POST['titel']) > 70)
				echo '<p>De titel mag maximaal 70 tekens lang zijn. <a href="javascript:history.back()">Ga terug</a></p>';
			elseif (empty($_POST['beschrijving']))
				echo '<p>De korte beschrijving is verplicht. <a href="javascript:history.back()">Ga terug</a></p>';
			elseif (strlen($_POST['beschrijving']) > 160)
				echo '<p>De korte beschrijving mag maximaal 160 tekens lang zijn. <a href="javascript:history.back()">Ga terug</a></p>';
			elseif (empty($_POST['content']))
				echo '<p>De inhoud is verplicht. <a href="javascript:history.back()">Ga terug</a></p>';
			else
			{
				$link->query("INSERT INTO paginas (titel, beschrijving, content) VALUES ('".strip_tags($_POST['titel'])."', '".strip_tags($_POST['beschrijving'])."', '".strip_tags($_POST['content'])."');");
				echo '<p class="green">De pagina is toegevoegd&hellip;</p>';
				header('refresh: 2; url = "'.$_SERVER['REQUEST_URI'].'"');
			}
		}
		else
		{
			echo '
				<form action="'.$_SERVER['REQUEST_URI'].'" method="post">
					<label for="titel">Titel:</label>
					<input type="text" id="titel" name="titel" maxlength="70" /><br />

					<label for="beschrijving">Korte beschrijving:</label>
					<input type="text" id="beschrijving" name="beschrijving" maxlength="160" /><br />

					<label for="content">Inhoud:</label>
					<textarea id="content" name="content" rows="10" cols="10"></textarea><br />

					<input type="submit" name="submit" value="Pagina toevoegen" />
				</form>
			</article>';
		}
	}
}
elseif(isset($_GET['pagina']) && $_GET['pagina'] == "fotoalbum")
{
	htmlOpenen('Fotoalbum');

	echo '
			<article>
				<h3>Overzicht</h3>
				<ul id="list">';

	$result = $link->query('SELECT * FROM '.$_GET['pagina'].'');
	while($record = $result->fetch_array())
	{
		echo '
					<li><a href="index.php?pagina='.$_GET['pagina'].'&actie=bewerken&id='.$record['id'].'">'.date("d-m-y",strtotime($record['datum'])).': '.afkappen($record['titel'],20).'</a></li>';	
	}

	echo '
					<li><a href="index.php?pagina='.$_GET['pagina'].'" id="new">Foto toevoegen</a></li>	
				</ul>
			</article>
			<article class="doubleSize">';

	if(isset($_GET['actie']) && $_GET['actie'] == "bewerken" && isset($_GET['id']))
	{
		$result = $link->query('SELECT * FROM fotoalbum WHERE id='.$_GET['id'].';');
		$record = $result->fetch_array();

		echo '
				<h3>Foto bewerken</h3>';

		if(isset($_POST['submit']))
		{
			if (empty($_POST['titel']))
				echo '<p>De titel is verplicht. <a href="javascript:history.back()">Ga terug</a></p>';
			elseif (strlen($_POST['titel']) > 70)
				echo '<p>De titel mag maximaal 70 tekens lang zijn. <a href="javascript:history.back()">Ga terug</a></p>';
			elseif(isset($_FILES['plaatje']) && $_FILES['foto']['type']!="image/jpeg")
				echo '<p>Alleen JPG afbeeldingen zijn toegestaan. <a href="javascript:history.back()">Ga terug</a></p>';
			elseif(isset($_FILES['plaatje']) && $_FILES['foto']['size'] > 200000)
				echo '<p>Alleen JPG afbeeldingen zijn toegestaan. <a href="javascript:history.back()">Ga terug</a></p>';
			else
			{
				$link->query("UPDATE fotoalbum SET titel = '".strip_tags($_POST['titel'])."' WHERE id=".$_GET['id'].";");	
				echo '<p class="green">De foto is gewijzigd&hellip;</p>';

				if (isset($_FILES['plaatje']) && file_exists('../images/'.$_GET['id'].'.jpg'))
					unlink('../images/'.$_GET['id'].'.jpg');

				move_uploaded_file($_FILES['foto']['tmp_name'], '../images/'.$_GET['id'].'.jpg');

				header('refresh: 2; url = "'.$_SERVER['REQUEST_URI'].'"');
			}
		}
		else
		{
			echo '
				<form action="'.$_SERVER['REQUEST_URI'].'" method="post" enctype="multipart/form-data">
					<label for="titel">Titel:</label>
					<input type="text" id="titel" name="titel" maxlength="70" value="'.$record['titel'].'" /><br />

					<label for="foto">Foto:</label>
					<input type="file" id="foto" name="foto" /><br />

					<input type="submit" name="submit" value="Wijzigingen opslaan" /> <a href="'.$_SERVER['PHP_SELF'].'?pagina=fotoalbum&actie=verwijderen&id='.$_GET['id'].'" class="deleteButton">Foto verwijderen</a>
				</form>
			</article>';
		}
	}
	elseif(isset($_GET['actie']) && $_GET['actie'] == "verwijderen" && isset($_GET['id']))
	{
			$link->query("DELETE FROM fotoalbum WHERE id=".$_GET['id'].";");
			unlink('../images/'.$_GET['id'].'.jpg');
			echo '
				<h3>Foto verwijderen</h3>	
				<p class="green">De foto is verwijderd.</p>';
	}
	else
	{

		echo '
				<h3>Foto toevoegen</h3>';

		if(isset($_POST['submit']))
		{
			if (empty($_POST['titel']))
				echo '<p>De titel is verplicht. <a href="javascript:history.back()">Ga terug</a></p>';
			elseif (strlen($_POST['titel']) > 70)
				echo '<p>De titel mag maximaal 70 tekens lang zijn. <a href="javascript:history.back()">Ga terug</a></p>';
			elseif (!isset($_FILES['foto']))
				echo '<p>Het is verplicht om een foto te selecteren. <a href="javascript:history.back()">Ga terug</a></p>';
			elseif($_FILES['foto']['type']!="image/jpeg")
				echo '<p>Alleen JPG afbeeldingen zijn toegestaan. <a href="javascript:history.back()">Ga terug</a></p>';
			elseif($_FILES['foto']['size'] > 200000)
				echo '<p>Alleen JPG afbeeldingen zijn toegestaan. <a href="javascript:history.back()">Ga terug</a></p>';
			else
			{
				$link->query("INSERT INTO fotoalbum (titel, datum) VALUES ('".strip_tags($_POST['titel'])."', NOW());");
				echo '<p class="green">De foto is toegevoegd&hellip;</p>';
				move_uploaded_file($_FILES['foto']['tmp_name'], '../images/'.mysqli_insert_id($link).'.jpg');
				header('refresh: 2; url = "'.$_SERVER['REQUEST_URI'].'"');
			}
		}
		else
		{
			echo '
				<form action="'.$_SERVER['REQUEST_URI'].'" method="post" enctype="multipart/form-data">
					<label for="titel">Titel:</label>
					<input type="text" id="titel" name="titel" maxlength="70" /><br />

					<label for="foto">Foto:</label>
					<input type="file" id="foto" name="foto" /><br />

					<input type="submit" name="submit" value="Foto toevoegen" />
				</form>
			</article>';
		}
	}
}
else
{
	htmlOpenen('Welkom');

	echo '
				<p>Welkom in het beheersysteem van uw website.</p>';
}

# geef de HTML code voor het sluiten van de pagina weer
htmlSluiten();
?>
