<?php
function singlePhotoFillerColor(): string
{
    $colors = ['#f4afa6', '#A6C8F4', '#A6F4C8', '#F4E1A6', '#D9A6F4', '#f4a6d5'];
    return $colors[array_rand($colors)];
}

$singlePhotoColors = [
    singlePhotoFillerColor(),
    singlePhotoFillerColor(),
    singlePhotoFillerColor(),
    singlePhotoFillerColor(),
];
?>

<div class="modalOverlay" id="curatedModalOverlay">
    <div class="modalOuterContainer">
        <div class="modalCard">

            <button type="button" class="modalCloseBtn" onclick="closeCuratedModal()">
                <img src="../assets/images/pinkLogo.png" alt="Logo" class="modalLogoIcon">
            </button>

            <!-- Photo Gallery Side -->
            <div class="modalPhotoSide">
                <div class="modalPhotoFiller" id="curatedModalPhoto" style="background-color: <?= $singlePhotoColors[0] ?>;">
                    <div class="photoGradientOverlay"></div>
                    <div class="photoNavRow">
                        <button type="button" class="photoNavBtn" onclick="prevCuratedPhoto()">
                            <img src="../assets/images/arrowIcon.png" alt="Arrow Icon" class="arrowIcon" style="width: 24px; height: auto; margin-right: 8px;">
                            Previous Image
                        </button>
                        <button type="button" class="photoNavBtn" onclick="nextCuratedPhoto()">
                            Next Image
                            <img src="../assets/images/arrowIcon.png" alt="Arrow Icon" class="arrowIcon" style="transform: rotate(180deg); width: 24px; height: auto; margin-left: 8px;">
                        </button>
                    </div>
                </div>
            </div>

            <!-- Details Side -->
            <div class="modalDetailsSide">
                <div class="modalHeaderRow">
                    <div class="cardTitle">CURATED FOR YOU</div>
                </div>

                <div class="detailRow">
                    <div class="detailCol">
                        <div class="detailLabel">NAME</div>
                        <div class="detailValue"><?= htmlspecialchars($recentVouch['name'] ?? 'John Smith') ?></div>
                    </div>
                    <div class="detailCol">
                        <div class="detailLabel">AGE</div>
                        <div class="detailValue"><?= htmlspecialchars($recentVouch['age'] ?? 24) ?></div>
                    </div>
                </div>

                <div class="detailRow">
                    <div class="detailCol">
                        <div class="detailLabel">LOCATION</div>
                        <div class="detailValue"><?= htmlspecialchars($recentVouch['location'] ?? 'Chicago, USA') ?></div>
                    </div>
                    <div class="detailCol">
                        <div class="detailLabel">GENDER</div>
                        <div class="detailValue"><?= htmlspecialchars($recentVouch['gender'] ?? 'Male') ?></div>
                    </div>
                </div>

                <div class="detailRow">
                    <div class="detailCol">
                        <div class="detailLabel">OCCUPATION</div>
                        <div class="detailValue"><?= htmlspecialchars($recentVouch['occupation'] ?? 'Content Creator') ?></div>
                    </div>
                    <div class="detailCol">
                        <div class="detailLabel">HOBBIES</div>
                        <div class="detailValue"><?= htmlspecialchars($recentVouch['hobbies'] ?? 'Hiking') ?></div>
                    </div>
                </div>

                <div class="detailBlock">
                    <div class="detailLabel">BIO</div>
                    <div class="detailQuote">"Great at cooking and a dog dad of 2"</div>
                </div>

                <div class="detailBlock">
                    <div class="detailLabel">LOOKING FOR</div>
                    <div class="detailQuote">"Someone who doesn't take themselves too serious and is willing to go on weekend hikes with me"</div>
                </div>

                <!-- Single's Action Buttons -->
                <div class="actionBtnGroup">
                    <button type="button" class="submitBtn btnNextProfile" id="btnNextProfile" onclick="nextCuratedProfile()">
                        <div class="btnIcon">
                            <img src="../assets/images/whiteLogoL.png" alt="Vouch Icon" class="vouchSmIcon" style="width: 12px; height: auto; margin-right: 8px;">
                        </div> 

                        NEXT PROFILE 

                        <div class="btnIcon">
                            <img src="../assets/images/whiteLogoR.png" alt="Vouch Icon" class="vouchSmIcon" style="width: 12px; height: auto; margin-right: 8px;">
                        </div>
                    </button>
                    
                    <button type="button" class="submitBtn btnRequestVouch" id="btnRequestVouch" onclick="requestVouch(); closeCuratedModal();">
                        <div class="btnIcon">
                            <img src="../assets/images/whiteLogoL.png" alt="Vouch Icon" class="vouchSmIcon" style="width: 12px; height: auto; margin-right: 4px;">
                        </div> 

                        REQUEST VOUCH 

                        <div class="btnIcon">
                            <img src="../assets/images/whiteLogoR.png" alt="Vouch Icon" class="vouchSmIcon" style="width: 12px; height: auto; margin-right: 4px;">
                        </div>
                    </button>
                </div>

                <!-- Footer Progress Tracker -->
                <div class="modalProgressSection">
                    <div class="progressBarTrack">
                        <div class="progressBarFill" style="width: 33%;"></div>
                    </div>
                    <div class="progressText">1 of 3</div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    const singlePhotoColors = <?= json_encode($singlePhotoColors) ?>;
    let singlePhotoIndex = 0;

    function openCuratedModal() {
        singlePhotoIndex = 0;
        document.getElementById('curatedModalPhoto').style.backgroundColor = singlePhotoColors[0];
        document.getElementById('curatedModalOverlay').classList.add('active');
    }

    function closeCuratedModal() {
        document.getElementById('curatedModalOverlay').classList.remove('active');
    }

    function nextCuratedPhoto() {
        singlePhotoIndex = (singlePhotoIndex + 1) % singlePhotoColors.length;
        document.getElementById('curatedModalPhoto').style.backgroundColor = singlePhotoColors[singlePhotoIndex];
    }

    function prevCuratedPhoto() {
        singlePhotoIndex = (singlePhotoIndex - 1 + singlePhotoColors.length) % singlePhotoColors.length;
        document.getElementById('curatedModalPhoto').style.backgroundColor = singlePhotoColors[singlePhotoIndex];
    }

    function nextCuratedProfile() {
        alert("Loading next profile...");
    }

    function requestVouch() {
        alert("Vouch request sent to your Matchmaker!");
    }
</script>