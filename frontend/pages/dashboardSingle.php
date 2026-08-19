<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../backend/config/database.php';
require_once __DIR__ . '/../../backend/models/user.php';
require_once __DIR__ . '/../../backend/models/vouching.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$db = Database::getConnection();
$currentProfileId = (int)$_SESSION['user_id']; 

// Single Profile
$pStmt = $db->prepare("
    SELECT user_id, full_name, bio, picture_url, location, gender, occupation, hobbies,
    TIMESTAMPDIFF(YEAR, STR_TO_DATE(birth_year, '%Y'), CURDATE()) as age 
    FROM Profiles 
    WHERE user_id = ?
");
$pStmt->bind_param("i", $currentProfileId);
$pStmt->execute();
$myProfileData = $pStmt->get_result()->fetch_assoc();
$pStmt->close();

$actualUserId = $myProfileData['user_id'] ?? $currentProfileId;

$vouchingModel = new Vouching($db);
$matchingCandidates = $vouchingModel->getMatchingCandidatesForSingle($actualUserId);

$myProfile = [
    'name' => $myProfileData['full_name'] ?? $_SESSION['userName'] ?? 'User',
    'bio' => !empty($myProfileData['bio']) ? $myProfileData['bio'] : 'No bio set yet.',
    'photo' => $myProfileData['picture_url'] ?? null,
    'age' => $myProfileData['age'] ?? 'N/A',
    'location' => $myProfileData['location'] ?? 'N/A',
    'gender' => $myProfileData['gender'] ?? 'N/A',
    'occupation' => $myProfileData['occupation'] ?? 'N/A',
    'hobbies' => $myProfileData['hobbies'] ?? 'N/A'
];

// Matchmaker Profile
$mStmt = $db->prepare("
    SELECT p.full_name, p.bio, p.picture_url, p.credentials
    FROM Account_Linking al 
    JOIN Profiles p ON al.matchmaker_user_id = p.user_id 
    WHERE al.single_user_id = ? OR al.single_user_id = ?
");
$mStmt->bind_param("ii", $actualUserId, $currentProfileId);
$mStmt->execute();
$matchmakerData = $mStmt->get_result()->fetch_assoc();
$mStmt->close();

$matchmakerText = 'Enter your link code in profile setup to connect with a matchmaker';
if (!empty($matchmakerData)) {
    if (!empty($matchmakerData['credentials'])) {
        $matchmakerText = $matchmakerData['credentials'];
    } elseif (!empty($matchmakerData['bio'])) {
        $matchmakerText = $matchmakerData['bio'];
    } else {
        $matchmakerText = 'No credentials set yet';
    }
}

$myMatchmaker = [
    'name' => $matchmakerData['full_name'] ?? 'Matchmaker',
    'bio' => $matchmakerData['bio'] ?? $matchmakerText,
    'photo' => $matchmakerData['picture_url'] ?? null
];

// Vouch History
$vouchHistory = [];
$vStmt = $db->prepare("
    SELECT p.full_name, 
           p.picture_url,
           p.location,
           p.gender,
           p.occupation,
           p.hobbies,
           TIMESTAMPDIFF(YEAR, STR_TO_DATE(p.birth_year, '%Y'), CURDATE()) as age, 
           v.timestamp, 
           v.status, 
           v.matchmaker_note
    FROM Vouching v
    JOIN Profiles p ON v.candidate_user_id = p.user_id
    WHERE v.requesting_single_id = ? OR v.requesting_single_id = ?
    ORDER BY v.timestamp DESC
");

if ($vStmt) {
    $vStmt->bind_param("ii", $actualUserId, $currentProfileId);
    $vStmt->execute();
    $res = $vStmt->get_result();
    while ($row = $res->fetch_assoc()) {
        $vouchHistory[] = [
            'name' => $row['full_name'],
            'photo' => $row['picture_url'] ?? null,
            'age' => $row['age'] ?? '-',
            'location' => $row['location'] ?? '-',
            'gender' => $row['gender'] ?? '-',
            'occupation' => $row['occupation'] ?? '-',
            'hobbies' => $row['hobbies'] ?? '-',
            'date' => date('d.m.y', strtotime($row['timestamp'])),
            'status' => ucfirst($row['status']),
            'note' => $row['matchmaker_note'] ?? 'No note left.'
        ];
    }
    $vStmt->close();
}

$recentVouch = $vouchHistory[0] ?? [
    'name' => 'Candidate',
    'photo' => null,
    'age' => '-',
    'location' => '-',
    'gender' => '-',
    'occupation' => '-',
    'hobbies' => '-',
    'bio' => '-',
    'lookingFor' => '-',
    'note' => 'Your matchmaker has not vouched for anyone yet.'
];

$pendingVouchCount = count(array_filter($vouchHistory, fn($v) => strtolower($v['status']) === 'pending'));
$waitingOnThemCount = count(array_filter($vouchHistory, fn($v) => strtolower($v['status']) === 'accepted'));
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vouch Single's Dashboard</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Google+Sans+Flex:opsz,wght@6..144,1..1000&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Urbanist:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

    <!-- CSS -->
    <link rel="stylesheet" href="browseCandidatesModalSingle.css?v=2">
    <link rel="stylesheet" href="dashboardSingle.css?v=4">
</head>

<body>
    <div class="dashboardBody">
        <a href="logout.php" class="logoutBtn">
            <img src="../assets/images/logoutIcon.svg" alt="Logout icon">
            <span>LOG OUT</span>
        </a>
        
        <div class="dashboardGrid">
        
            <!-- My Profile -->
            <div class="dashCard" id="myProfileCard">
                <a href="setupProfile.php" class="editIconBtn" title="Edit my profile"><img src="../assets/images/pinkEditIcon.png" alt="Edit"></a>
                <?php if (!empty($myProfile['photo'])): ?>
                    <img src="<?= htmlspecialchars($myProfile['photo']) ?>" class="profileImg" alt="Profile Photo">
                <?php else: ?>
                    <div class="profileImg" style="background-color: var(--petal);"></div>
                <?php endif; ?>
                <div class="cardLabel">MY PROFILE</div>
                <div class="cardName"><?= htmlspecialchars($myProfile['name']) ?></div>
                <div class="cardBio">"<?= htmlspecialchars($myProfile['bio']) ?>"</div>
            </div>
        




            <!-- My Matchmaker -->
            <div class="dashCard" id="myMatchmakerCard">
                <a href="setUpProfile.php" class="editIconBtn" title="Edit"><img src="../assets/images/orangeEditIcon.png" alt="Edit"></a>
                <?php if (!empty($myMatchmaker['photo'])): ?>
                    <img src="<?= htmlspecialchars($myMatchmaker['photo']) ?>" class="profileImg" alt="Matchmaker Photo" style="object-fit: cover;">
                <?php else: ?>
                    <div class="profileImg" style="background-color: var(--sunkissed);"></div>
                <?php endif; ?>
                <div class="cardLabel cardLabelOrange">MY MATCHMAKER</div>
                <div class="cardName"><?= htmlspecialchars($myMatchmaker['name']) ?></div>
                <div class="cardBio">"<?= htmlspecialchars($myMatchmaker['bio']) ?>"</div>
            </div>
        




            <!-- Stats & Nudge -->
            <div class="dashCard" id="statsCard">
                <div class="statsRow">
                    <div class="statBlock">
                        <div class="statNumber statNumberOrange" id="pendingVouchCount"><?= $pendingVouchCount ?></div>
                        <div class="statLabel" style="color: var(--sunkissed);">PENDING VOUCH</div>
                        <div class="statSub">Waiting on your Matchmaker to review</div>
                    </div>
                    <div class="statBlock">
                        <div class="statNumber statNumberPink" id="waitingOnThemCount"><?= $waitingOnThemCount ?></div>
                        <div class="statLabel" style="color: var(--petal);">WAITING ON THEM</div>
                        <div class="statSub">Waiting on candidate's Matchmaker to review</div>
                    </div>
                </div>
                <hr class="statsDivider">
                <button type="button" class="roleBtn btnMatchmaker" id="nudgeBtn" onclick="nudgeMatchmaker()">
                    <img src="../assets/images/bellIcon.png" alt="Nudge icon" style="width: 16px; height: auto; margin-right: 8px;">
                    NUDGE <?= strtoupper(htmlspecialchars(explode(' ', $myMatchmaker['name'])[0])) ?>
                </button>
            </div>
        





            <!-- Most Recent Vouch -->
            <div class="dashCard" id="mostRecentVouchCard">
                <div class="cardTitle">MOST RECENT VOUCH</div>
                <a href="javascript:void(0)" class="browseCandidatesBtn" onclick="openCuratedModal()"><img src="../assets/images/pinkLogo.png" alt="Browse Candidates"></a>

                <div class="recentVouchLayout">
                    <?php if (!empty($recentVouch['photo'])): ?>
                        <img src="<?= htmlspecialchars($recentVouch['photo']) ?>" class="profileLarge" alt="Candidate" style="object-fit: cover;">
                    <?php else: ?>
                        <div class="profileLarge" style="background-color: var(--dragonfruit)"></div>
                    <?php endif; ?>
                    <div class="recentVouchDetails">
                        <div class="detailRow">
                            <div class="detailCol">
                                <div class="detailLabel">NAME</div>
                                <div class="detailValue"><?= htmlspecialchars($recentVouch['name']) ?></div>
                            </div>
                            <div class="detailCol">
                                <div class="detailLabel">AGE</div>
                                <div class="detailValue"><?= htmlspecialchars($recentVouch['age']) ?></div>
                            </div>
                        </div>
                        <div class="detailRow">
                            <div class="detailCol">
                                <div class="detailLabel">LOCATION</div>
                                <div class="detailValue"><?= htmlspecialchars($recentVouch['location']) ?></div>
                            </div>
                            <div class="detailCol">
                                <div class="detailLabel">GENDER</div>
                                <div class="detailValue"><?= htmlspecialchars($recentVouch['gender']) ?></div>
                            </div>
                        </div>
                        <div class="detailRow">
                            <div class="detailCol">
                                <div class="detailLabel">OCCUPATION</div>
                                <div class="detailValue"><?= htmlspecialchars($recentVouch['occupation']) ?></div>
                            </div>
                            <div class="detailCol">
                                <div class="detailLabel">HOBBIES</div>
                                <div class="detailValue"><?= htmlspecialchars($recentVouch['hobbies']) ?></div>
                            </div>
                        </div>

                        <div class="matchmakerNoteLabel"><?= strtoupper(htmlspecialchars(explode(' ', $myMatchmaker['name'])[0])) ?> SAYS...</div>
                        <div class="detailValue"><?= htmlspecialchars($recentVouch['note']) ?></div>
                    </div>
                </div>

                <div class="actionBtnGroup">
                    <button type="button" class="submitBtn" id="messageBtn" onclick="messageCandidate()">
                        <img src="../assets/images/messageIcon.png" alt="Message icon" style="width: 16px; height: auto; margin-right: 8px;">
                        MESSAGE <?= strtoupper(htmlspecialchars(explode(' ', $recentVouch['name'])[0])) ?>
                    </button>
                </div>
            </div>
        




            <!-- Vouch History -->
            <div class="dashCard" id="vouchHistoryCard">
                <div class="cardTitle">VOUCH HISTORY</div>
        
                <table class="vouchHistoryTable">
                    <thead>
                        <tr>
                            <th>NAME</th>
                            <th>AGE</th>
                            <th>DATE</th>
                            <th>STATUS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($vouchHistory)): ?>
                            <tr>
                                <td colspan="4" style="text-align: center; padding: 24px; color: var(--coral); font-size: 14px;">
                                    No vouches recorded yet. Your matchmaker's suggestions will appear here!
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($vouchHistory as $row): ?>
                               <td class="historyNameCell">
                                    <?php if (!empty($row['photo'])): ?>
                                        <img src="<?= htmlspecialchars($row['photo']) ?>" class="profileSmall" alt="Thumbnail" style="object-fit: cover; width: 28px; height: 28px; border-radius: 50%; vertical-align: middle; margin-right: 8px;">
                                    <?php else: ?>
                                        <span class="profileSmall" style="background-color: var(--sunkissed);"></span>
                                    <?php endif; ?>
                                    <?= htmlspecialchars($row['name']) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($row['age']) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($row['date']) ?>
                                </td>

                                <td class="statusCell">
                                    <div class="statusInfoWrapper">
                                        <span class="statusTag status<?= htmlspecialchars($row['status']) ?>">
                                            <?= htmlspecialchars($row['status']) ?>
                                        </span>
                                        <div class="statusInfo">
                                            <strong style="font-weight: 600;"><?= strtoupper(htmlspecialchars(explode(' ', $myMatchmaker['name'])[0])) ?> SAYS:</strong> "<?= htmlspecialchars($row['note']) ?>"
                                        </div>
                                    </div>
                                </td>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <script>
        function nudgeMatchmaker() {
            alert("Nudge sent!");
        }
    
        function messageCandidate() {
            alert("Opening chat...");
        }
    </script>

<?php include 'browseCandidatesModalSingle.php'; ?>
</body>
</html>
