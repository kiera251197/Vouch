<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

$mySingle = [
    'name' => 'Jane Doe',
    'bio' => 'Fluent in sarcasm, romcom movie quotes and Spotify playlists',
];

$myProfile = [
    'name' => 'Sophia Walker',
    'bio' => "I'm filtering out the weirdos so you don't have to, thank me later mwah!!",
];

$awaitingVouchCount = 4;
$totalVetosCount = 12;

$currentCandidate = [
    'name' => 'John Smith',
    'age' => 24,
    'location' => 'Chicago, USA',
    'gender' => 'Male',
    'occupation' => 'Content Creator',
    'hobbies' => 'Hiking',
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
                <div class="profileImg" style="background-color: #DE6993;"></div>
                <div class="cardLabel">MY SINGLE</div>
                <div class="cardName"><?= htmlspecialchars($mySingle['name']) ?></div>
                <div class="cardBio">"<?= htmlspecialchars($mySingle['bio']) ?>"</div>
            </div>
        




            <!-- My Profile (Matchmaker side badabing) -->
            <div class="dashCard" id="myProfileCard">
                <a href="setUpProfile.php" class="editIconBtn" title="Edit Profile"><img src="../assets/images/orangeEditIcon.png" alt="Edit"></a>
                <div class="profileImg" style="background-color: #D95205;"></div>
                <div class="cardLabel cardLabelOrange">MY PROFILE</div>
                <div class="cardName"><?= htmlspecialchars($myProfile['name']) ?></div>
                <div class="cardBio">"<?= htmlspecialchars($myProfile['bio']) ?>"</div>
            </div>




        
            <!-- Stats & Ping -->
            <div class="dashCard" id="statsCard">
                <div class="statsRow">
                    <div class="statBlock">
                        <div class="statNumber statNumberOrange" id="awaitingVouchCount"><?= $awaitingVouchCount ?></div>
                        <div class="statLabel" style="color: var(--sunkissed);">AWAITING VOUCH</div>
                        <div class="statSub">Profiles waiting for you to review</div>
                    </div>
                    <div class="statBlock">
                        <div class="statNumber statNumberPink" id="totalVetosCount"><?= $totalVetosCount ?></div>
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
                <div class="profileLarge" style="background-color: #8E1353"></div>

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
                <button type="button" class="submitBtn" id="btnVeto" onclick="vetoCandidate()">
                    <img src="../assets/images/xIcon.png" alt="X icon" style="width: 14px; height: auto; margin-right: 8px;"> 
                    VETO
                </button>
                
                <button type="button" class="submitBtn" id="btnVouch" onclick="vouchCandidate()">
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
