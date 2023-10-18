<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Define the path to the log file
    $logFilePath = "submission_log.txt";

    // Define the cooldown period in seconds (7 days)
    $cooldownPeriod = 7 * 24 * 60 * 60;

    // Get the user's IP address
    $userIP = $_SERVER["REMOTE_ADDR"];

    // Initialize an empty array for log data
    $logData = [];

    // Check if the log file exists and is not empty
    if (file_exists($logFilePath) && filesize($logFilePath) > 0) {
        // Read the log file and unserialize the data
        $logContents = file_get_contents($logFilePath);
        if ($logContents !== false) {
            $logData = unserialize($logContents);
            if (!is_array($logData)) {
                $logData = []; // Reset to an empty array if unserialization fails
            }
        }
    }

    // Check if the user's IP is in the log and if the cooldown period has passed
    if (isset($logData[$userIP]) && (time() - $logData[$userIP]) < $cooldownPeriod) {
        // The user is on cooldown; they can't submit the form again yet
        echo "You cannot submit the form again so soon.";
        exit;
    }

    // The user is not on cooldown; proceed with form processing
    $answers = [];

    if (!empty($_POST["q1"])) {
            $answers[] = "What is your Discord username#tag?: " . $_POST["q1"];
        }
        if (!empty($_POST["q2"])) {
            $answers[] = "What is your Discord ID?: " . $_POST["q2"];
        }
        if (!empty($_POST["q3"])) {
            $answers[] = "How old are you? (Precisely): " . $_POST["q3"];
        }
        if (!empty($_POST["q4"])) {
            $answers[] = "What position are you applying for?: " . $_POST["q4"];
        }
        if (!empty($_POST["q5"])) {
            $answers[] = "Do you agree to join the staff server if you get accepted?: " . $_POST["q5"];
        }

        if (!empty($_POST["q6"])) {
            $answers[] = "Do you have any experience? If yes, state each server and your rank: " . $_POST["q6"];
        }

        if (!empty($_POST["q7"])) {
            $answers[] = "How well can you use Arcane commands? 1 being not good, 5 being very well: " . $_POST["q7"];
        }

        if (!empty($_POST["q8"])) {
            $answers[] = "In your own words, what is the job as Moderator?: " . $_POST["q8"];
        }

        if (!empty($_POST["q9"])) {
            $answers[] = "Why do you want to be a Moderator?: " . $_POST["q9"];
        }

        if (!empty($_POST["q10"])) {
            $answers[] = "You see someone post NSFW anywhere in the server, what do you do?: " . $_POST["q10"];
        }

        if (!empty($_POST["q11"])) {
            $answers[] = "You see a user being racist to another user in the server, what do you do?: " . $_POST["q11"];
        }

        if (!empty($_POST["q12"])) {
            $answers[] = "You see a user cyberbullying another user, what do you do?: " . $_POST["q12"];
        }

        if (!empty($_POST["q13"])) {
            $answers[] = "You see a user spam in chat, what do you do?: " . $_POST["q13"];
        }

        if (!empty($_POST["q14"])) {
            $answers[] = "You see impersonation in the server, what do you do?: " . $_POST["q14"];
        }

        if (!empty($_POST["q15"])) {
            $answers[] = "Do you agree that if you don't meet the monthly agenda for multiple months in a row, you'll be terminated?: " . $_POST["q15"];
        }

        if (!empty($_POST["q16"])) {
            $answers[] = "Do you agree that if you are too inactive without an LOA, you’ll be terminated?: " . $_POST["q16"];
        }

    if (!empty($answers)) {
        // Prepare answers for Discord webhook as an embed with spacing between questions
        $embed = [
            "title" => "BloxyLeaks Moderation Application",
            "description" => implode("\n\n", $answers), // Add "\n\n" for spacing
            "color" => 0x3366ff // You can customize the color if desired
        ];

        // Prepare the payload for the webhook
        $webhookData = [
            "embeds" => [$embed]
        ];

        // Send data to Discord webhook
        $webhookURL = "https://discord.com/api/webhooks/1154525940546805800/XcO6YMt6BsBMtAf-ZR5JrKOy1pyvk2PAgWLH6qNo_aziJ5uMEL2awBl5ZRNnYAm6jhz1"; // Replace with your webhook URL
        $ch = curl_init($webhookURL);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($webhookData));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        // Check if the request was successful
        if ($response === false) {
            echo "Failed to send data to Discord webhook.";
        } else {
            echo "Answers submitted successfully!";
        }

        // Log the user's IP and the current timestamp to prevent rapid submissions
        $logData[$userIP] = time();
        file_put_contents($logFilePath, serialize($logData));
    } else {
        echo "No answers submitted.";
    }
} else {
    echo "Invalid request.";
}
?>
