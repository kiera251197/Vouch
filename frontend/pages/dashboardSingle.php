<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
 
$myProfile = [
    'name' => 'Jane Doe',
    'bio' => 'Fluent in sarcasm, romcom movie quotes and Spotify playlists',
];
 
$myMatchmaker = [
    'name' => 'Sophia Walker',
    'bio' => "I'm filtering out the weirdos so you don't have to, thank me later mwah!!",
];
 
$pendingVouchCount = 4;
$waitingOnThemCount = 2;
 
$recentVouch = [
    'name' => 'John Smith',
    'age' => 24,
    'location' => 'Chicago, USA',
    'gender' => 'Male',
    'occupation' => 'UX Designer',
    'hobbies' => 'Hiking',
    'note' => 'Good pick, he has dogs and loves hiking just like you!',
];
 
$vouchHistory = [
    ['name' => 'Nicholas', 'age' => 27, 'date' => '20.07.26', 'status' => 'Rejected', 'note' => 'Not really your type, prefers dogs over cats'],
    ['name' => 'Jack',     'age' => 22, 'date' => '16.07.26', 'status' => 'Accepted', 'note' => 'Super charming and loves romcoms too ahhh!'],
    ['name' => 'Daniel',   'age' => 25, 'date' => '02.07.26', 'status' => 'Accepted', 'note' => 'Likes hiking too and lives nearby'],
    ['name' => 'Ethan',    'age' => 23, 'date' => '28.06.26', 'status' => 'Rejected', 'note' => 'Looking for something a bit more casual'],
    ['name' => 'Aiden',    'age' => 24, 'date' => '27.06.26', 'status' => 'Accepted', 'note' => 'Perfect match on paper!'],
];
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

<div class="dashboardBody">
    
    <div class="dashboardGrid">
    
        <!-- My Profile -->
        <div class="dashCard" id="myProfileCard">
            <a href="setupProfile.php" class="editIconBtn" title="Edit my profile"><img src="../assets/images/pinkEditIcon.png" alt="Edit"></a>
            <div class="profileImg" style="background-color: #DE6993;"></div>
            <div class="cardLabel">MY PROFILE</div>
            <div class="cardName"><?= htmlspecialchars($myProfile['name']) ?></div>
            <div class="cardBio">"<?= htmlspecialchars($myProfile['bio']) ?>"</div>
        </div>
    




        <!-- My Matchmaker -->
        <div class="dashCard" id="myMatchmakerCard">
            <a href="setUpProfile.php" class="editIconBtn" title="Edit"><img src="../assets/images/orangeEditIcon.png" alt="Edit"></a>
            <div class="profileImg" style="background-color: #D95205;"></div>
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
                <div class="profileLarge" style="background-color: #8E1353"></div>
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
                    <?php foreach ($vouchHistory as $row): ?>
                        <tr>
                            <td class="historyNameCell">
                                <span class="profileSmall" style="background-color: #F28806;"></span>
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
                                        <strong style="font-weight: 600;">SOPHIA SAYS:</strong> "<?= htmlspecialchars($row['note']) ?>"
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
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
