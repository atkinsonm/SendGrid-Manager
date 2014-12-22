<?php
/**
 * Template for displaying all pages
 *
 * This is a page to display all SendGrid recipient
 * lists and allow the user to add email addresses to
 * one or multiple lists. Modified from page.php from
 * Twenty Eleven 1.0. Written by Michael Meluso.
 * www.michaelmeluso.com
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 */

get_header(); ?>

		<div id="primary">
			<div id="content" role="main">

				<?php while ( have_posts() ) : the_post(); ?>
                <?php wp_enqueue_script('select-all.js') ?>
					<?php
                    require "sendgrid-remove-email.php";
                    require "sendgrid-get-lists.php";
                    // SendGrid list custom code
                    // SendGrid Add
                    $delims = array(
                        'comma'         => ',',
                        'semicolon'     => ';',
                        'space'         => ' '
                    );
                    if (empty($_POST)) {
                        echo "<h1 class=\"entry-title\">Remove Email Address from Lists</h1>";
                        echo "<button onclick=\"selectAll(true)\">Select all</button><br>";
                        echo "<button onclick=\"selectAll(false)\">Deselect all</button><br>";
                        echo " 
                            <script>
                            function selectAll(checktoggle) {
                                var checkboxes = new Array(); 
                                checkboxes = document.getElementsByClassName('checkable');

                                for (var i=0; i<checkboxes.length; i++)  {
                                    checkboxes[i].checked = checktoggle;
                                }
                            }
                            </script>
                        ";
                        echo "<form id=\"form_remove_email\" method=\"post\" action=\"".get_permalink()."\">";
                        echo "<input type=\"text\" name=\"address\" size=\"100\" placeholder=\"Email address, or multiple addresses separated by a ";
                        $numDelims = count($delims);
                        $i = 0;
                        foreach ($delims as $name => $value) {
                            if(++$i != $numDelims) {
                                echo $name  . ", "; 
                            } else {
                                echo "or " . $name;
                            }
                        }
                        echo "\"><br>";
                        $i = 0;
                        $lists = getLists();
                        foreach ($lists as &$value) {
                            $value = substr($value, 0, strlen($value)-1);
                            $valueF = str_replace(' ', '-', $value);
                            echo "<input class=\"checkable\" type=\"checkbox\" name=\"list$i\" value=$valueF>$value<br>";
                            $i = $i + 1;
                        }
                    
                        echo "<input type=\"submit\" value=\"Remove\">";
                        echo "</form>";
                    } else {
                        foreach($_POST as $name => $value) {
                            //If the data begins with "address", use it.
                            if ($name == "address") {
                                foreach ($delims as $name => $entry) {
                                    $emailDelimited = str_replace($entry, ",", $value);
                                }
                                $emailBulk = explode(",", $emailDelimited);
                            } elseif (strlen(strstr($name,"list"))>0) {
                                $valueF = str_replace('-', ' ', $value);
                                foreach ($emailBulk as $email) {
                                    $resultMsg = removeFromList($email, $valueF);
                                    echo $resultMsg . "<br>";
                                }
                            }
                        }
                    }
                    echo "<br><a href=\"http://www.solec.org/index.php/sendgrid-manager/\">Back to SendGrid Manager</a>";
                    ?>

					<?php comments_template( '', true ); ?>

				<?php endwhile; // end of the loop. ?>

			</div><!-- #content -->
		</div><!-- #primary -->

<?php get_footer(); ?>