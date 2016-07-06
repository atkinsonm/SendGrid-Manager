<?php
/**
 * @package WordPress
 */
get_header(); ?>

<div class="content" role="main">

<?php if (have_posts()): ?>
	<?php while (have_posts()): the_post(); ?>
	
		<?php
                    require "sendgrid-get-lists.php";
                    require "sendgrid-add-email.php";
                    require "sendgrid-remove-email.php";
                    // SendGrid list custom code
                    // SendGrid Search and Replace
					
                    if (empty($_POST)) {
					?>
                        <h1 class="entry-title">Search All Lists for an Email Address</h1>
                        <form id="form_search" method="post" action="<?php echo get_permalink() ?>">
                        <input type="text" name="address" placeholder="Email address"><br>
                        <input type="submit" name="search" value="Search">
                        </form>
					<?php
                    } else if (isset($_POST['search'])) {
                        $lists = getLists(null);
                        $hits = "";
                        $email = "";
                        foreach($_POST as $name => $value) {
                            if ($name == "address") {
                                $email = $value; ?>
                                <h1 class="entry-title">Search Results for "<?php echo $email ?>"</h1>
							<?php
								$deepsearch = true;
                                foreach ($lists as $value) {
                                    $value = substr($value, 0, strlen($value)-1);
                                    $result = getEmails($value, $email);
									
									if (is_null($result) && $deepsearch == true) { // Perform a deep search
										$listContents = getEmails($value);
										$result = array_filter($listContents, function($var) use ($email) { return preg_match("/\b$email\b/i", $var); });
										
										if (!(is_null($result))) { // If is still null, then none found
											foreach ($result as $match) {
												$valueF = str_replace(' ', '-', $value);
                                        		$hits = $hits . "," . $valueF;
											?>
												<?php echo $email ?> was found in the following lists:<br>
                                				<table class="recip-table">
                                				<tr class = "recip-table-top">
                                				<th><strong>Recipient List</strong></th>
                                				<th><strong>Remove</strong></th>
                                				</tr>
                                        		<tr class="recip-table-tr">
												<form id="form_search" method="post" action="<?php echo get_permalink() ?>">
                                        		<td><?php echo $match ?><input type="hidden" name="address" value=<?php echo $match ?>></td>
                                        		<td><input type="submit" name="search" value="Search again for <?php echo $match ?>"></td>
												</form>
												</tr>
											<?php
											}
										} else {
											<?php echo $email ?> was not found in any lists.<br>
										}
										
									} else if (!is_null($result)) { // Found using SendGrid search
										$valueF = str_replace(' ', '-', $result);
                                        $hits = $hits . "," . $valueF;
										$deepsearch = false;
									?>
                                        <tr class="recip-table-tr">
										<form id="form_remove" method="post" action="<?php echo get_permalink() ?>">
                                        <td><?php echo $result ?><input type="hidden" name="email" value=<?php echo $email ?>></td>
                                        <td><input type="submit" name="<?php echo $valueF ?>" value="Remove from <?php echo $result ?>"></td>
										</form>
                                        </tr>
									<?php
									}
                                }
                            }
                        } ?>
                        </table>
						<br>
					<?php
						if (!$deepsearch) {
						?>
                        Replace all instances with:<br>
                        <form id="form_replace" method="post" action="<?php echo get_permalink() ?>">
                        <input type="hidden" name="old" value=<?php echo $email ?>>
                        <input type="text" name="new" placeholder="Email address"><br>
                        <input type="hidden" name="hits" value=<?php echo $hits ?>>
                        <input type="submit" name="replace" value="Replace"><br>
                        </form>
					
					<?php
						} else { ?>
							<strong>Please search again for a specific email address to use the search-and-replace or remove features.</strong>
					<?php
						}
                    } else if (isset($_POST['replace'])) {
                        echo "<h1 class=\"entry-title\">Replace</h1>";
                        $listStr = $_POST['hits'];
                        $listStr = substr($listStr, 1);
                        $lists = explode(",", $listStr);
                        $oldEmail = $_POST['old'];
                        $newEmail = $_POST['new'];
                        foreach ($lists as $value) {
                            $value = str_replace('-', ' ', $value);
                            $resultMsg = removeFromList($oldEmail, $value);
                            echo $resultMsg . "<br>";
                            $resultMsg = addToList($newEmail, $value);
                            echo $resultMsg . "<br>";
                        }
                    } else if (isset($_POST)) {
                        echo "<h1 class=\"entry-title\">Removing</h1>";
                        foreach($_POST as $name => $value) {                   
			    if ($name == "email") {
                                $email = $value;
                            } else {
                                $valueF = str_replace('-', ' ', $value);
                                $list = substr($valueF, 12);
                                $resultMsg = removeFromList($email, $list);
                                echo $resultMsg . "<br>";
                            }
                        }
                    }?>
                    <br><a href="<?php bloginfo('wpurl'); ?>/sendgrid-manager/">Back to SendGrid Manager</a>
		
	<?php endwhile; ?>
	
<?php else: ?>

    <?php get_template_part('notfound'); ?>
    
<?php endif; ?>

</div><!--END content-->

<?php get_sidebar(); ?>
<?php get_footer(); ?>