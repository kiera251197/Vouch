<?php
    $pendingCandidates = $pendingCandidates ?? [];
?>

<!-- Vouch Alert Modal -->
<div class="modalOverlayMini" id="customAlertOverlay" style="z-index: 10000;">
    <div class="modalOuterContainerMini">
        <div class="modalCardMini">
            <div class="cardTitleMini" id="customAlertTitle">
                NOTICE
            </div>

            <p id="customAlertMessageMini"></p>

            <button type="button" class="submitBtn" onclick="closeCustomAlert()" style="align-self: center; min-width: 100px;">

                <div class="btnIcon">
                    <img src="../assets/images/whiteLogoL.png" alt="Vouch Icon" class="vouchSmIcon" style="width: 12px; height: auto; margin-right: 8px;">
                </div>

                OKAY

                <div class="btnIcon">
                    <img src="../assets/images/whiteLogoR.png" alt="Vouch Icon" class="vouchSmIcon" style="width: 12px; height: auto; margin-left: 8px;">
                </div>

            </button>
        </div>
    </div>
</div>

<!-- Main Candidates Modal -->
<div class="modalOverlay" id="candidateModalOverlay">
    <div class="modalOuterContainer">
        <div class="modalCard">

            <button type="button" class="modalCloseBtn" onclick="closeCandidateModal()">
                <img src="../assets/images/pinkLogo.png" alt="Logo" class="modalLogoIcon">
            </button>

            <!-- Photo Gallery -->
            <div class="modalPhotoSide">
                <div class="modalPhotoFiller" id="candidateModalPhoto">
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
                        <div class="detailValue" id="mcName">-</div>
                    </div>
                    <div class="detailCol">
                        <div class="detailLabel">AGE</div>
                        <div class="detailValue" id="mcAge">-</div>
                    </div>
                </div>

                <div class="detailRow">
                    <div class="detailCol">
                        <div class="detailLabel">LOCATION</div>
                        <div class="detailValue" id="mcLocation">-</div>
                    </div>
                    <div class="detailCol">
                        <div class="detailLabel">GENDER</div>
                        <div class="detailValue" id="mcGender">-</div>
                    </div>
                </div>

                <div class="detailRow">
                    <div class="detailCol">
                        <div class="detailLabel">OCCUPATION</div>
                        <div class="detailValue" id="mcOccupation">-</div>
                    </div>
                    <div class="detailCol">
                        <div class="detailLabel">HOBBIES</div>
                        <div class="detailValue" id="mcHobbies">-</div>
                    </div>
                </div>

                <div class="detailBlock">
                    <div class="detailLabel">BIO</div>
                    <div class="detailQuote" id="mcBio">-</div>
                </div>

                <div class="detailBlock">
                    <div class="detailLabel">LOOKING FOR</div>
                    <div class="detailQuote" id="mcLookingFor">-</div>
                </div>

                <div class="actionBtnGroup">
                    <button type="button" class="submitBtn" id="btnVeto" onclick="reviewCurrentCandidate('veto')">
                        <img src="../assets/images/xIcon.png" alt="X icon" style="width: 12px; height: auto; margin-right: 8px;"> 
                        VETO
                    </button>
                    <button type="button" class="submitBtn" id="btnVouch" onclick="reviewCurrentCandidate('vouch')">
                        <img src="../assets/images/tickIcon.png" alt="Tick icon" style="width: 14px; height: auto; margin-right: 8px;"> 
                        VOUCH
                    </button>
                </div>

                <!-- Footer Progress Tracker -->
                <div class="modalProgressSection">
                    <div class="progressBarTrack">
                        <div class="progressBarFill" id="candidateProgressFill" style="width: 33%;"></div>
                    </div>
                    <div class="progressText" id="candidateProgressText">0 of 0</div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    const candidateColourPool = ['#8E1353', '#D95205', '#F28806', '#F2B94B', '#F26D6D', '#DE6993', '#E0C1C9'];
    let pendingCandidates = <?= json_encode($pendingCandidates) ?>;
    let candidateIndex = 0;
    let photoIndex = 0;

    function showAlert(message, title = 'NOTICE') {
        document.getElementById('customAlertTitle').textContent = title;
        document.getElementById('customAlertMessageMini').textContent = message;
        document.getElementById('customAlertOverlay').classList.add('active');
    }

    function closeCustomAlert() {
        document.getElementById('customAlertOverlay').classList.remove('active');
    }

    function openCandidateModal() {
        candidateIndex = 0;
        photoIndex = 0;
        renderCandidate();
        document.getElementById('candidateModalOverlay').classList.add('active');
    }

    function closeCandidateModal() {
        document.getElementById('candidateModalOverlay').classList.remove('active');
    }

    function renderCandidate() {
        const total = pendingCandidates.length;

        if (total === 0) {
            document.getElementById('mcName').textContent = 'No Candidates Waiting';
            document.getElementById('mcAge').textContent = '-';
            document.getElementById('mcLocation').textContent = '-';
            document.getElementById('mcGender').textContent = '-';
            document.getElementById('mcOccupation').textContent = '-';
            document.getElementById('mcHobbies').textContent = '-';
            document.getElementById('mcBio').textContent = "Your Single hasn't requested any vouches yet.";
            document.getElementById('mcLookingFor').textContent = '-';
            document.getElementById('candidateProgressText').textContent = '0 of 0';
            document.getElementById('candidateProgressFill').style.width = '0%';
            const photoEl = document.getElementById('candidateModalPhoto');
            photoEl.style.backgroundImage = 'none';
            photoEl.style.backgroundColor = 'var(--blossom)';
            document.getElementById('btnVeto').disabled = true;
            document.getElementById('btnVouch').disabled = true;
            return;
        }

        document.getElementById('btnVeto').disabled = false;
        document.getElementById('btnVouch').disabled = false;

        const c = pendingCandidates[candidateIndex];

        document.getElementById('mcName').textContent = c.name;
        document.getElementById('mcAge').textContent = c.age;
        document.getElementById('mcLocation').textContent = c.location;
        document.getElementById('mcGender').textContent = c.gender;
        document.getElementById('mcOccupation').textContent = c.occupation;
        document.getElementById('mcHobbies').textContent = c.hobbies;
        document.getElementById('mcBio').textContent = c.bio;
        document.getElementById('mcLookingFor').textContent = c.lookingFor;

        document.getElementById('candidateProgressText').textContent = `${candidateIndex + 1} of ${total}`;
        document.getElementById('candidateProgressFill').style.width = `${((candidateIndex + 1) / total) * 100}%`;

        renderCandidatePhoto();
    }

    function renderCandidatePhoto() {
        const photoEl = document.getElementById('candidateModalPhoto');
        const c = pendingCandidates[candidateIndex];

        if (c && c.photos && c.photos.length > 0) {
            const currentImg = c.photos[photoIndex] || c.photos[0];
            photoEl.style.backgroundImage = `url('${currentImg}')`;
            photoEl.style.backgroundSize = 'cover';
            photoEl.style.backgroundPosition = 'center';
        } else {
            photoEl.style.backgroundImage = 'none';
            photoEl.style.backgroundColor = candidateColourPool[candidateIndex % candidateColourPool.length];
        }
    }

    function nextCandidatePhoto() {
        if (!pendingCandidates.length) return;
        const c = pendingCandidates[candidateIndex];
        if (c && c.photos && c.photos.length > 1) {
            photoIndex = (photoIndex + 1) % c.photos.length;
            renderCandidatePhoto();
        }
    }

    function prevCandidatePhoto() {
        if (!pendingCandidates.length) return;
        const c = pendingCandidates[candidateIndex];
        if (c && c.photos && c.photos.length > 1) {
            photoIndex = (photoIndex - 1 + c.photos.length) % c.photos.length;
            renderCandidatePhoto();
        }
    }
</script>