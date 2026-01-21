<?php
session_start();
require_once '../models/User.php';

if(!isset($_SESSION['user_id'])) {
    header("Location: ../views/home.php");
    exit;
}

if(isset($_POST['update'])){
    $name = trim($_POST['name']);
    $location = trim($_POST['location']);
    $profilePicture = null;

    // Validate inputs
    if(empty($name)) {
        $_SESSION['profile_update_error'] = 'Full name is required.';
        header("Location: ../views/profile.php");
        exit;
    }

    if(empty($location)) {
        $_SESSION['profile_update_error'] = 'Location is required.';
        header("Location: ../views/profile.php");
        exit;
    }

    // Handle profile picture upload
    if(isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $maxSize = 5 * 1024 * 1024; // 5MB

        if(in_array($_FILES['profile_picture']['type'], $allowedTypes) && $_FILES['profile_picture']['size'] <= $maxSize) {
            $uploadDir = '../storage/resources/images/profiles/';

            // Create directory if it doesn't exist
            if(!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            // Generate unique filename
            $fileExtension = pathinfo($_FILES['profile_picture']['name'], PATHINFO_EXTENSION);
            $fileName = 'profile_' . $_SESSION['user_id'] . '_' . time() . '.' . $fileExtension;
            $filePath = $uploadDir . $fileName;

            // Read file content and save using file_put_contents
            $fileContent = file_get_contents($_FILES['profile_picture']['tmp_name']);
            if($fileContent !== false && file_put_contents($filePath, $fileContent)) {
                $profilePicture = $fileName;

                // Remove old profile picture if exists
                if(!empty($_SESSION['profile_picture'])) {
                    $oldFile = $uploadDir . $_SESSION['profile_picture'];
                    if(file_exists($oldFile)) {
                        unlink($oldFile);
                    }
                }
            }
        }
    }

    // Update user in database
    $userModel = new User();
    $updateData = [
        'full_name' => $name,
        'location' => $location
    ];

    if($profilePicture) {
        $updateData['profile_picture'] = $profilePicture;
    }

    if($userModel->updateProfile($_SESSION['user_id'], $updateData)) {
        // Update session data
        $_SESSION['user_name'] = $name;
        $_SESSION['location'] = $location;
        if($profilePicture) {
            $_SESSION['profile_picture'] = $profilePicture;
        }

        $_SESSION['profile_update_success'] = 'Profile updated successfully!';
    } else {
        $_SESSION['profile_update_error'] = 'Failed to update profile. Please try again.';
    }

    header("Location: ../views/profile.php");
    exit;
}
?>
