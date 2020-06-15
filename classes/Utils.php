<?php
/*******************************************************
 * Project:     ggg-cc-portfolio
 * File:        Utils.php
 * Author:      Your name
 * Date:        2020-06-01
 * Version:     1.0.0
 * Description:
 *******************************************************/

class Utils
{
    /**
     * Sanitize method
     *
     * Accept a test string and pass it through the htmlspecialcharacters and strip_tags
     * methods to remove HTML/XML type tags, and escape / replace characters such as the
     * backtick `, tick ', and so forth with their encodes equivalents.
     *
     * This forms a level of security to the application, but it is not perfect.
     *
     * @param  $text
     * @return string
     */
    public static function sanitize($text): string
    {
        return trim(htmlspecialchars(strip_tags($text))) ?? '';
    }


    /**
     * Display list of messages
     *
     * The message list is an array of messages.
     *
     * Each message in the list has (and array) key-value pair.
     *      Key - the type of message (warning, danger, success, info)
     *      Value - the message text itself.
     *
     *      $messages = [
     *          0 => [ 'danger' => 'Critical error message' ],
     *          1 => [ 'success' => 'Success message' ],
     *          2 => [ 'info' => 'Informational message' ],
     *          3 => [ 'warning' => 'Warning message' ],
     *      ]
     *
     *      There are also other alert/message types such as:
     *          primary, secondary, light and dark - they may be of use
     *
     * @param  array  $messageList
     */
    public static function messages(array $messageList)
    {
        foreach ($messageList as $message) {
            foreach ($message as $type => $text) {
                echo "<p class='alert alert-{lower($type)}'>{$type}: {$text}</p>";
            }
        }
    }















    /**
     * Retrieves a category by its ID from the endpoint
     * @return array    Results in the format: ['error' => bool, 'message' => array, 'category' => Category]
     */
    public static function getCategoryByEndpointId()
    {
        $id = basename(Utils::sanitize($_SERVER['PHP_SELF']));

        if (is_numeric($id) && floor($id) == $id && (int)$id > 0) {
            $id = (int)$id;

            // try to retrieve category from database
            $db = new Database();
            $connection = $db->getConnection();

            $category = new Category($connection);

            $result = $category->readOne($id);

            if (!$result['error']) {
                // category was found, map values
                $category->id = $result['category']->id;
                $category->code = $result['category']->code;
                $category->name = $result['category']->name;
                $category->icon = $result['category']->icon;
                $category->description = $result['category']->description;
                $category->createdAt = $result['category']->created_at;
                $category->updatedAt = $result['category']->updated_at;

                return ['error' => false, 'category' => $category];
            } else {
                // category was not found, show errors
                return ['error' => true, 'message' => $result['message']];
            }
        } else {
            // invalid category ID provided in the URL
            return ['error' => true, 'message' => ['Danger' => 'Invalid category ID']];
        }
    }

    /**
     * Tries to find a category by its ID in the URL endpoint.
     *
     * Basically, a wrapper for `Utils::getCategoryByEndpointId()`
     * @param  array  $messages Error messages to be displayed
     * @return array  Results in the format: ['error' => bool, 'messages' => array, 'category' => Category]
     */
    public static function tryFetchCategory(array $messages)
    {
        $category = '';
        try {
            $result = Utils::getCategoryByEndpointId();

            if (!$result['error']) {
                $category = $result['category'];

                return [
                    'error' => false,
                    'category' => $category
                ];
            } else {
                $messages[] = $result['message'];

                return [
                    'error' => true,
                    'messages' => $messages
                ];
            }
        } catch (\PDOException $e) {
            $messages[] = [
                'Danger' => 'Could not connect to the database'
            ];

            return [
                'error' => true,
                'messages' => $messages
            ];
        } catch (\Exception $e) {
            $messages[] = [
                'Warning' => 'Could not fetch category'
            ];

            return [
                'error' => true,
                'messages' => $messages
            ];
        }
    }























    /* End of Utils Class */

}