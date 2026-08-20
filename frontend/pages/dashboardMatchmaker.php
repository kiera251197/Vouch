<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();

require_once __DIR__ . '/../../backend/config/database.php';
require_once __DIR__ . '/../../backend/models/user.php';
require_once __DIR__ . '/../../backend/models/vouching.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$db = Database::getConnection();
$matchmakerUserId = (int)$_SESSION['user_id'];

// Matchmaker Profile
$mStmt = $db->prepare("
    SELECT full_name, bio, picture_url, credentials 
    FROM Profiles 
    WHERE user_id = ?
");
$mStmt->bind_param("i", $matchmakerUserId);
$mStmt->execute();
$matchmakerData = $mStmt->get_result()->fetch_assoc();
$mStmt->close();

$myProfile = [
    'name' => $matchmakerData['full_name'] ?? $_SESSION['userName'] ?? 'Matchmaker',
    'bio' => $matchmakerData['bio'] ?? $matchmakerData['credentials'] ?? 'Filtering out the bad setups!',
    'photo' => $matchmakerData['picture_url'] ?? null
];

// Single Profile
$sStmt = $db->prepare("
    SELECT p.user_id, p.full_name, p.bio, p.picture_url
    FROM Account_Linking al
    JOIN Profiles p ON al.single_user_id = p.user_id
    WHERE al.matchmaker_user_id = ?
");
$sStmt->bind_param("i", $matchmakerUserId);
$sStmt->execute();
$singleData = $sStmt->get_result()->fetch_assoc();
$sStmt->close();

$singleUserId = $singleData['user_id'] ?? null;
$mySingle = [
    'name' => $singleData['full_name'] ?? 'No Single Linked',
    'bio' => $singleData['bio'] ?? 'Waiting for a Single to connect with your link code.',
    'photo' => $singleData['picture_url'] ?? null
];

// Vouch History & Candidate Records
$vouchHistory = [];
if ($singleUserId) {
    $vStmt = $db->prepare("
        SELECT p.user_id,
               p.full_name,
               p.picture_url,
               p.location,
               p.gender,
               p.occupation,
               p.hobbies,
               (YEAR(CURDATE()) - CAST(birth_year AS UNSIGNED)) as age,
               v.timestamp,
               v.status,
               v.matchmaker_note
        FROM Vouching v
        JOIN Profiles p ON v.candidate_user_id = p.user_id
        WHERE v.requesting_single_id = ?
        ORDER BY v.timestamp DESC
    ");
    $vStmt->bind_param("i", $singleUserId);
    $vStmt->execute();
    $res = $vStmt->get_result();

    while ($row = $res->fetch_assoc()) {
        $vouchHistory[] = [
            'candidate_id' => $row['user_id'],
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

$pendingCandidates = [];
if ($singleUserId) {
    $vouchingModel = new Vouching($db);
    $pendingCandidates = $vouchingModel->getPendingCandidates($singleUserId);
}

$currentCandidate = $pendingCandidates[0] ?? [
    'id' => null,
    'name' => 'No Active Candidates',
    'age' => '-',
    'location' => '-',
    'gender' => '-',
    'occupation' => '-',
    'hobbies' => '-',
    'bio' => '-',
    'lookingFor' => '-'
];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vouch Matchmakers's Dashboard</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Google+Sans+Flex:opsz,wght@6..144,1..1000&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Urbanist:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

    <!-- CSS -->
     <link rel="stylesheet" href="browseCandidatesModal.css?v=3">
    <link rel="stylesheet" href="dashboardMatchmaker.css?v=3">
</head>

<body>
    <div class="dashboardBody">
        <a href="logout.php" class="logoutBtn">
            <img src="../assets/images/logoutIcon.svg" alt="Logout icon">
            <span>LOG OUT</span>
        </a>
        
        <div class="dashboardGrid">
        
            <!-- My Single -->
            <div class="dashCard" id="mySingleCard">
                <a href="setupProfile.php" class="editIconBtn" title="Edit Single Profile"><img src="../assets/images/pinkEditIcon.png" alt="Edit"></a>
                <?php if (!empty($mySingle['photo'])): ?>
                    <img src="<?= htmlspecialchars($mySingle['photo']) ?>" class="profileImg" alt="Single Photo" style="object-fit: cover;">
                <?php else: ?>
                    <div class="profileImg" style="background-color: var(--petal);"></div>
                <?php endif; ?>
                <div class="cardLabel">MY SINGLE</div>
                <div class="cardName"><?= htmlspecialchars($mySingle['name']) ?></div>
                <div class="cardBio">"<?= htmlspecialchars($mySingle['bio']) ?>"</div>
            </div>
        




            <!-- My Profile (Matchmaker side badabing) -->
            <div class="dashCard" id="myProfileCard">
                <a href="setUpProfile.php" class="editIconBtn" title="Edit Profile"><img src="../assets/images/orangeEditIcon.png" alt="Edit"></a>
                <?php if (!empty($myProfile['photo'])): ?>
                    <img src="<?= htmlspecialchars($myProfile['photo']) ?>" class="profileImg" alt="Matchmaker Photo" style="object-fit: cover;">
                <?php else: ?>
                    <div class="profileImg" style="background-color: var(--sunkissed);"></div>
                <?php endif; ?>
                <div class="cardLabel cardLabelOrange">MY PROFILE</div>
                <div class="cardName"><?= htmlspecialchars($myProfile['name']) ?></div>
                <div class="cardBio">"<?= htmlspecialchars($myProfile['bio']) ?>"</div>
            </div>




        
            <!-- Stats & Ping -->
            <div class="dashCard" id="statsCard">
                <div class="statsRow">
                    <div class="statBlock">
                        <div class="statNumber statNumberOrange" id="awaitingVouchCount"><?= count($pendingCandidates) ?></div>
                        <div class="statLabel" style="color: var(--sunkissed);">AWAITING VOUCH</div>
                        <div class="statSub">Profiles waiting for you to review</div>
                    </div>
                    <div class="statBlock">
                        <div class="statNumber statNumberPink" id="totalVetosCount"><?= count(array_filter($vouchHistory, fn($v) => strtolower($v['status']) === 'veto' || strtolower($v['status']) === 'vetoed')) ?></div>
                        <div class="statLabel" style="color: var(--petal);">TOTAL VETOS</div>
                        <div class="statSub">Candidates that didn't quite meet the criteria</div>
                    </div>
                </div>
                <hr class="statsDivider">
                <button type="button" class="roleBtn btnMatchmaker" id="pingBtn" onclick="pingSingle()">
                    <img src="../assets/images/bellIcon.png" alt="Ping icon" style="width: 16px; height: auto; margin-right: 8px;">
                    PING <?= strtoupper(htmlspecialchars(explode(' ', $mySingle['name'])[0])) ?>
                </button>
            </div>




        
            <!-- Current Candidate Profile -->
            <div class="dashCard" id="candidateCard">
            <div class="cardHeaderRow">
                <div class="cardTitle">CURRENT CANDIDATE PROFILE</div>
                <a href="javascript:void(0)" class="browseCandidatesBtn" onclick="openCandidateModal()"><img src="../assets/images/pinkLogo.png" alt="View Candidate"></a>
            </div>

            <div class="recentVouchLayout">
                <?php if (!empty($currentCandidate['photo'])): ?>
                    <img src="<?= htmlspecialchars($currentCandidate['photo']) ?>" class="profileLarge" alt="Candidate Photo" style="object-fit: cover;">
                <?php else: ?>
                    <div class="profileLarge" style="background-color: var(--dragonfruit);"></div>
                <?php endif; ?>

                <div class="recentVouchDetails">
                    <div class="detailRow">
                        <div class="detailCol">
                            <div class="detailLabel">NAME</div>
                            <div class="detailValue"><?= htmlspecialchars($currentCandidate['name']) ?></div>
                        </div>
                        <div class="detailCol">
                            <div class="detailLabel">AGE</div>
                            <div class="detailValue"><?= htmlspecialchars($currentCandidate['age']) ?></div>
                        </div>
                    </div>
                    <div class="detailRow">
                        <div class="detailCol">
                            <div class="detailLabel">LOCATION</div>
                            <div class="detailValue"><?= htmlspecialchars($currentCandidate['location']) ?></div>
                        </div>
                        <div class="detailCol">
                            <div class="detailLabel">GENDER</div>
                            <div class="detailValue"><?= htmlspecialchars($currentCandidate['gender']) ?></div>
                        </div>
                    </div>
                    <div class="detailRow">
                        <div class="detailCol">
                            <div class="detailLabel">OCCUPATION</div>
                            <div class="detailValue"><?= htmlspecialchars($currentCandidate['occupation']) ?></div>
                        </div>
                        <div class="detailCol">
                            <div class="detailLabel">HOBBIES</div>
                            <div class="detailValue"><?= htmlspecialchars($currentCandidate['hobbies']) ?></div>
                        </div>
                    </div>

                    <div class="matchmakerNoteLabel">MESSAGE TO <?= strtoupper(htmlspecialchars(explode(' ', $mySingle['name'])[0])) ?></div>
                    <input type="text" class="matchmakerInput" placeholder="Leave a message for <?= strtoupper(htmlspecialchars(explode(' ', $mySingle['name'])[0])) ?>..." id="matchmakerNoteInput">
                </div>
            </div>

            <div class="actionBtnGroup">
                <button type="button" class="submitBtn" id="dashBtnVeto" onclick="vetoCandidate()">
                    <img src="../assets/images/xIcon.png" alt="X icon" style="width: 14px; height: auto; margin-right: 8px;"> 
                    VETO
                </button>
                
                <button type="button" class="submitBtn" id="dashBtnVouch" onclick="vouchCandidate()">
                    <img src="../assets/images/tickIcon.png" alt="Tick icon" style="width: 16px; height: auto; margin-right: 8px;"> 
                    VOUCH
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
                                <td colspan="4" style="text-align: center; padding: 24px; color: var(--sunkissed); font-size: 14px;">
                                    No candidates reviewed yet for your Single.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($vouchHistory as $row): ?>
                            <tr>
                                <td class="historyNameCell">
                                    <?php if (!empty($row['photo'])): ?>
                                        <img src="<?= htmlspecialchars($row['photo']) ?>" class="profileSmall" alt="Thumbnail" style="object-fit: cover;">
                                    <?php else: ?>
                                        <span class="profileSmall" style="background-color: #F28806;"></span>
                                    <?php endif; ?>
                                    <?= htmlspecialchars($row['name']) ?>
                                </td>
                                <td><?= htmlspecialchars($row['age']) ?></td>
                                <td><?= htmlspecialchars($row['date']) ?></td>
                                <td class="statusCell">
                                    <div class="statusInfoWrapper">
                                        <span class="statusTag status<?= htmlspecialchars($row['status']) ?>">
                                            <?= htmlspecialchars($row['status']) ?>
                                        </span>
                                        <div class="statusInfo">
                                            <strong style="font-weight: 600;"><?= strtoupper(htmlspecialchars(explode(' ', $myProfile['name'])[0])) ?> SAYS:</strong> 
                                            "<?= htmlspecialchars($row['note']) ?>"
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <script>
        function pingSingle() {
            alert("Ping sent to Jane!");
        }
    
        function vetoCandidate() {
            alert("Candidate Vetoed.");
        }

        function vouchCandidate() {
            alert("Candidate Vouched!");
        }
    </script>

<?php include 'browseCandidatesModal.php'; ?>
</body>
</html>
