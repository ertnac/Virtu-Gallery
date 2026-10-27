<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../db_connect.php';

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }

    $id = filter_input(INPUT_POST, 'artistId', FILTER_VALIDATE_INT);
    $first_name = filter_input(INPUT_POST, 'artistFirstName', FILTER_SANITIZE_SPECIAL_CHARS);
    $middle_name = filter_input(INPUT_POST, 'artistMiddleName', FILTER_SANITIZE_SPECIAL_CHARS);
    $last_name = filter_input(INPUT_POST, 'artistLastName', FILTER_SANITIZE_SPECIAL_CHARS);
    $suffix = filter_input(INPUT_POST, 'artistSuffix', FILTER_SANITIZE_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, 'artistEmail', FILTER_SANITIZE_EMAIL);
    $username = filter_input(INPUT_POST, 'artistUsername', FILTER_SANITIZE_SPECIAL_CHARS);
    $municipality = filter_input(INPUT_POST, 'artistMunicipality', FILTER_SANITIZE_SPECIAL_CHARS);
    $province = filter_input(INPUT_POST, 'artistProvince', FILTER_SANITIZE_SPECIAL_CHARS);
    $bio = filter_input(INPUT_POST, 'artistBio', FILTER_SANITIZE_SPECIAL_CHARS);
    $website = filter_input(INPUT_POST, 'artistWebsite', FILTER_SANITIZE_URL);
    $profile_picture = filter_input(INPUT_POST, 'artistProfilePicture', FILTER_SANITIZE_URL);
    $verified = filter_input(INPUT_POST, 'artistStatus', FILTER_VALIDATE_INT);
    $stage_name = filter_input(INPUT_POST, 'artistStageName', FILTER_SANITIZE_SPECIAL_CHARS);
    $art_style = filter_input(INPUT_POST, 'artistArtStyle', FILTER_SANITIZE_SPECIAL_CHARS);
    $portfolio_link = filter_input(INPUT_POST, 'artistPortfolioLink', FILTER_SANITIZE_URL);

    if (!$id || !$first_name || !$last_name || !$email) {
        throw new Exception('Missing required fields');
    }

    // Update user in users table
    $sqlUser = "UPDATE users SET 
            first_name = :first_name,
            middle_name = :middle_name,
            last_name = :last_name,
            suffix = :suffix,
            email = :email,
            username = :username,
            municipality = :municipality,
            province = :province,
            bio = :bio,
            website = :website,
            profile_picture = :profile_picture
            WHERE id = :id";
    $stmtUser = $pdo->prepare($sqlUser);
    $stmtUser->execute([
        ':id' => $id,
        ':first_name' => $first_name,
        ':middle_name' => $middle_name ?: null,
        ':last_name' => $last_name,
        ':suffix' => $suffix ?: null,
        ':email' => $email,
        ':username' => $username ?: null,
        ':municipality' => $municipality ?: '',
        ':province' => $province ?: '',
        ':bio' => $bio ?: null,
        ':website' => $website ?: null,
        ':profile_picture' => $profile_picture ?: null
    ]);

    // Upsert artist details in artists table
    $stmtArtist = $pdo->prepare("
        INSERT INTO artists (user_id, stage_name, art_style, portfolio_link, verified)
        VALUES (:user_id, :stage_name, :art_style, :portfolio_link, :verified)
        ON CONFLICT(user_id) DO UPDATE SET
            stage_name = excluded.stage_name,
            art_style = excluded.art_style,
            portfolio_link = excluded.portfolio_link,
            verified = excluded.verified
    ");
    try {
        $stmtArtist->execute([
            ':user_id' => $id,
            ':stage_name' => $stage_name ?: null,
            ':art_style' => $art_style ?: null,
            ':portfolio_link' => $portfolio_link ?: null,
            ':verified' => $verified ?? 0
        ]);
    } catch (Exception $ex) {
        // Fallback for MySQL syntax if needed
        $stmtArtistMysql = $pdo->prepare("
            INSERT INTO artists (user_id, stage_name, art_style, portfolio_link, verified)
            VALUES (:user_id, :stage_name, :art_style, :portfolio_link, :verified)
            ON DUPLICATE KEY UPDATE
                stage_name = VALUES(stage_name),
                art_style = VALUES(art_style),
                portfolio_link = VALUES(portfolio_link),
                verified = VALUES(verified)
        ");
        $stmtArtistMysql->execute([
            ':user_id' => $id,
            ':stage_name' => $stage_name ?: null,
            ':art_style' => $art_style ?: null,
            ':portfolio_link' => $portfolio_link ?: null,
            ':verified' => $verified ?? 0
        ]);
    }

    echo json_encode([
        'success' => true,
        'message' => 'Artist updated successfully'
    ]);
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
