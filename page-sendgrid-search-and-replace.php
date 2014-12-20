<?php
/**
 * Template for displaying all pages
 *
 * This is a page to search all SendGrid recipient
 * lists for an email address and allow the user to
 * replace that address with another. Modified from 
 * page.php from Twenty Eleven 1.0. Written by Michael Meluso.
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
                    require "sendgrid-get-lists.php";
                    require "sendgrid-add-email.php";
                    require "sendgrid-remove-email.php";
                    // SendGrid list custom code
                    // SendGrid Search and Replace
                    if (empty($_POST)) {
                        echo "<h1 class=\"entry-title\">Search All Lists for an Email Address</h1>";
                        echo "<form id=\"form_search\" method=\"post\" action=\"".get_permalink()."\">";
                        echo "<input type=\"text\" name=\"address\" placeholder=\"Email address\"><br>";
                        echo "<input type=\"submit\" name=\"search\" value=\"Search\">";
                        echo "</form>";
                    } else if (isset($_POST['search'])) {
                        $lists = getLists(null);
                        $hits = "";
                        $email = "";
                        foreach($_POST as $name => $value) {
                            if ($name == "address") {
                                $email = $value;
                                echo "<h1 class=\"entry-title\">Search Results for \"$email\"</h1>";
                                echo $email . " was found in the following lists:<br>";
                                echo "<form id=\"form_remove\" method=\"post\" action=\"".get_permalink()."\">";
                                echo "<table class=\"recip-table\">";
                                echo "<tr class = \"recip-table-top\">";
                                echo "<th><strong>Recipient List</strong></th>";
                                echo "<th><strong>Remove</strong></th>";
                                echo "</tr>";
                                foreach ($lists as $value) {
                                    $value = substr($value, 0, strlen($value)-1);
                                    $result = getEmails($value, $email);
                                    if (!(is_null($result))) {
                                        $valueF = str_replace(' ', '-', $result);
                                        $hits = $hits . "," . $valueF;
                                        echo "<tr class=\"recip-table-tr\">";
                                        echo "<td class=\"recip-table-td\">$result<input type=\"hidden\" name=\"email\" value=$email></td>";
                                        echo "<td class=\"recip-table-td\"><input type=\"submit\" name=$valueF value=\"Remove from $result \"></td>";
                                        echo "</tr>";
                                    }
                                }
                            }
                        }
                        echo "</table>";
                        echo "</form>";
                        echo "Replace all instances with:<br>";
                        echo "<form id=\"form_replace\" method=\"post\" action=\"".get_permalink()."\">";
                        echo "<input type=\"hidden\" name=\"old\" value=$email>";
                        echo "<input type=\"text\" name=\"new\" placeholder=\"Email address\"><br>";
                        echo "<input type=\"hidden\" name=\"hits\" value=$hits>";
                        echo "<input type=\"submit\" name=\"replace\" value=\"Replace\"><br>";
                        echo "</form>";
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
                                $list = substr($list, 0, strlen($list)-1);
                                $resultMsg = removeFromList($email, $list);
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