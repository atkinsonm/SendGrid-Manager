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

                    
					<?php
                    require "sendgrid-remove-list.php";
                    require "sendgrid-get-lists.php";
                    // SendGrid list custom code
                    // SendGrid Add 
                    if (empty($_POST)) {
                        echo "<h1 class=\"entry-title\">Remove Recipient List</h1>";
                        echo "<form id=\"form_remove_list\" method=\"post\" action=\"".get_permalink()."\">";
                    
                        $i = 0;
                        $lists = getLists(null);
                        foreach ($lists as &$value) {
                            $value = substr($value, 0, strlen($value)-1);
                            $valueF = str_replace(' ', '-', $value);
                            echo "<input type=\"checkbox\" name=\"list$i\" value=$valueF>$value<br>";
                            $i = $i + 1;
                        }
                    
                        echo "<input type=\"submit\" value=\"Remove - this action cannot be undone\">";
                        echo "</form>";
                    } else {
                        $lists = array();
                        foreach($_POST as $name => $value) {
                            //If the data begins with "address", use it.
                            if (strlen(strstr($name,"list"))>0) {
                                $valueF = str_replace('-', ' ', $value);
                                $resultMsg = removeList($valueF);
                                echo $resultMsg . "<br>";
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