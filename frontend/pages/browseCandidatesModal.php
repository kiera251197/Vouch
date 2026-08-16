<?php
function candidatePhotoFillerColor(): string
{
    $colors = ['#F4A6A6', '#A6C8F4', '#A6F4C8', '#F4E1A6', '#D9A6F4', '#F4A6D9'];
    return $colors[array_rand($colors)];
}

$candidatePhotoColors = [
    candidatePhotoFillerColor(),
    candidatePhotoFillerColor(),
    candidatePhotoFillerColor(),
    candidatePhotoFillerColor(),
];
?>

<div class="modalOverlay" id="candidateModalOverlay">
    <div class="modalOuterContainer">
        <div class="modalCard">

            <button type="button" class="modalCloseBtn" onclick="closeCandidateModal()">
                <img src="../assets/images/pinkLogo.png" alt="Logo" class="modalLogoIcon">
            </button>

            <!-- Photo Gallery -->
            <div class="modalPhotoSide">
                <div class="modalPhotoFiller" id="candidateModalPhoto" style="background-color: <?= $candidatePhotoColors[0] ?>;">
                    <div class="photoGradientOverlay"></div>
                    <div class="photoNavRow">
                        <button type="button" class="photoNavBtn" onclick="prevCandidatePhoto()">
                            <img src="../assets/images/arrowIcon.png" alt="Arrow Icon" class="arrowIcon" style="width: 24px; height: auto; margin-right: 8px;">
                            Previous Image
                        </button>
                        <button type="button" class="photoNavBtn" onclick="nextCandidatePhoto()">
                            Next Image
                            <img src="../assets/images/arrowIcon.png" alt="Arrow Icon" class="arrowIcon" style="transform: rotate(180deg); width: 24px; height: auto; margin-left: 8px;">
                        </button>
                    </div>
                </div>
            </div>

            <!-- Profile Info -->
            <div class="modalDetailsSide">
                <div class="modalHeaderRow">
                    <div class="cardTitle">CANDIDATE PROFILE</div>
                </div>

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

                <div class="detailBlock">
                    <div class="detailLabel">BIO</div>
                    <div class="detailQuote">"Great at cooking and a dog dad of 2"</div>
                </div>

                <div class="detailBlock">
                    <div class="detailLabel">LOOKING FOR</div>
                    <div class="detailQuote">"Someone who doesn't take themselves too serious and is willing to go on weekend hikes with me"</div>
                </div>

                <div class="actionBtnGroup">
                    <button type="button" class="submitBtn" id="btnVeto" onclick="vetoCandidate(); closeCandidateModal();">
                        <img src="../assets/images/xIcon.png" alt="X icon" style="width: 12px; height: auto; margin-right: 8px;"> 
                        VETO
                    </button>
                    <button type="button" class="submitBtn" id="btnVouch" onclick="vouchCandidate(); closeCandidateModal();">
                        <img src="../assets/images/tickIcon.png" alt="Tick icon" style="width: 14px; height: auto; margin-right: 8px;"> 
                        VOUCH
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
    const candidatePhotoColors = <?= json_encode($candidatePhotoColors) ?>;
    let candidatePhotoIndex = 0;

    function openCandidateModal() {
        candidatePhotoIndex = 0;
        document.getElementById('candidateModalPhoto').style.backgroundColor = candidatePhotoColors[0];
        document.getElementById('candidateModalOverlay').classList.add('active');
    }

    function closeCandidateModal() {
        document.getElementById('candidateModalOverlay').classList.remove('active');
    }

    function nextCandidatePhoto() {
        candidatePhotoIndex = (candidatePhotoIndex + 1) % candidatePhotoColors.length;
        document.getElementById('candidateModalPhoto').style.backgroundColor = candidatePhotoColors[candidatePhotoIndex];
    }

    function prevCandidatePhoto() {
        candidatePhotoIndex = (candidatePhotoIndex - 1 + candidatePhotoColors.length) % candidatePhotoColors.length;
        document.getElementById('candidateModalPhoto').style.backgroundColor = candidatePhotoColors[candidatePhotoIndex];
    }
</script>