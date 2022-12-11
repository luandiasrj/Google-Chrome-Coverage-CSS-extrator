<?php
// ===================================================
// This small code aims to generate a CSS file with only the rules applied in a page extracted from the report created by the coverage tool of google chrome. With @media rule support.
// ===================================================

$json = "coverage.json"; // The coverage.json file created by the coverage tool
$css_file = "coverage.css"; // The CSS file to be generated

// Open Css_file in w mode, will erase the file if it exists
$css = fopen($css_file, "w");
// Decode the json file
try {
    $json = json_decode(file_get_contents($json), true);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
    exit;
}

// Parse the json file
foreach ($json as $file) {
    // Check the extension of the file
    if (pathinfo($file["url"], PATHINFO_EXTENSION) == "css") {
        // Write the url of the file in the css file
        $ofile = " File: " . $file["url"];
        $repeat = str_repeat("-", strlen($ofile));
        fwrite($css, "/*" . $repeat . "\n" . $ofile . "\n " . $repeat . "*/\n");
        // Echo the url of the file
        echo "File: <a href='" .
            $file["url"] .
            "' target='_blank'>" .
            $file["url"] .
            "</a><br><hr><pre>";

        // Last @media position
        $last_media = 0;
        $opened = false;

        // Parse the ranges
        foreach ($file["ranges"] as $range) {
            // Get the length of the range
            $length = $range["end"] - $range["start"];
            // Get the text of the range
            $snippet = substr($file["text"], $range["start"], $length);
            // Get the last @media position before the range
            $last_media = strrpos(
                substr($file["text"], 0, $range["start"]),
                "@media"
            );
            // If there is a @media before the range
            if ($last_media) {
                // Get the text of the @media
                $media = substr(
                    $file["text"],
                    $last_media,
                    $range["start"] - $last_media
                );

                // if the first to appear after the range is a {, then the @media is not closed, so jump to the next range
                if (
                    strpos($file["text"], "{", $range["end"]) <
                    strpos($file["text"], "}", $range["end"])
                ) {
                    continue;
                }

                // If the @media is not closed
                if (substr_count($media, "{") > substr_count($media, "}")) {
                    // Close the @media
                    $snippet = $media . " " . $snippet . "\n}";
                }
            }

            echo $snippet . "<br>";
            fwrite($css, $snippet . "\n");
        }
        echo "</pre><hr>";
    }
}
// Echo the full path of the file
echo "<br>File generated: <a href='" .
    realpath($css_file) .
    "' target='_blank'>" .
    realpath($css_file) .
    "</a>";

// Close the file
fclose($css);