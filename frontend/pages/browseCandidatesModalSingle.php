<?php
    $matchingCandidates = $matchingCandidates ?? [];

    $singlePhotoColours = $singlePhotoColours ?? ['#8E1353', '#D95205', '#F28806', '#F2B94B', '#F26D6D', '#DE6993', '#E0C1C9'];
?>

<div class="modalOverlay" id="customAlertOverlay" style="z-index: 10000;">
    <div class="modalOuterContainer" style="max-width: 400px; width: 90%;">
        <div class="modalCard" style="flex-direction: column; padding: 24px; text-align: center;">
            <div class="cardTitle" style="margin-bottom: 12px;" id="customAlertTitle">NOTICE</div>
            <p id="customAlertMessage" style="font-size: 14px; margin-bottom: 20px; color: #333;"></p>
            <button type="button" class="submitBtn" onclick="closeCustomAlert()" style="align-self: center; min-width: 100px;">
                OK
            </button>
        </div>
    </div>
</div>

<div class="modalOverlay" id="curatedModalOverlay">
    <div class="modalOuterContainer">
        <div class="modalCard">

            <button type="button" class="modalCloseBtn" onclick="closeCuratedModal()">
                <img src="../assets/images/pinkLogo.png" alt="Logo" class="modalLogoIcon">
            </button>

            <!-- Photo Gallery Side -->
            <div class="modalPhotoSide" style="position: relative;">
                <div class="modalPhotoFiller" id="curatedModalPhoto">
                    <div class="photoGradientOverlay"></div>
                </div>
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

            <!-- Details Side -->
            <div class="modalDetailsSide">
                <div class="modalHeaderRow">
                    <div class="cardTitle">CURATED FOR YOU</div>
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

                <!-- Single's Action Buttons -->
                <div class="actionBtnGroup">
                    <button type="button" class="submitBtn" id="btnNextProfile" onclick="nextCuratedProfile()">
                        <div class="btnIcon">
                            <img src="../assets/images/whiteLogoL.png" alt="Vouch Icon" class="vouchSmIcon" style="width: 12px; height: auto; margin-right: 8px;">
                        </div> 

                        NEXT PROFILE 

                        <div class="btnIcon">
                            <img src="../assets/images/whiteLogoR.png" alt="Vouch Icon" class="vouchSmIcon" style="width: 12px; height: auto; margin-right: 8px;">
                        </div>
                    </button>
                    
                    <button type="button" class="submitBtn" id="btnRequestVouch" onclick="requestVouch(); closeCuratedModal();">
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
    const matchingCandidates = <?= json_encode(array_values($matchingCandidates ?? [])) ?>;
    const singlePhotoColours = <?= json_encode($singlePhotoColours ?? ['#8E1353', '#D95205', '#F28806', '#F2B94B', '#F26D6D', '#DE6993', '#E0C1C9']) ?>;
    let candidateIndex = 0;
    let photoIndex = 0;

    function showAlert(message, title = 'NOTICE') {
        document.getElementById('customAlertTitle').textContent = title;
        document.getElementById('customAlertMessage').textContent = message;
        document.getElementById('customAlertOverlay').classList.add('active');
    }

    function closeCustomAlert() {
        document.getElementById('customAlertOverlay').classList.remove('active');
    }

    window.openCuratedModal = function() {
        candidateIndex = 0;
        photoIndex = 0;
        renderCandidate();
        document.getElementById('curatedModalOverlay').classList.add('active');
    };

    window.closeCuratedModal = function() {
        document.getElementById('curatedModalOverlay').classList.remove('active');
    };

    function renderCandidate() {
        const hasCandidates = matchingCandidates && matchingCandidates.length > 0;
        const c = hasCandidates ? matchingCandidates[candidateIndex] : null;

        document.getElementById('mcName').textContent = c ? c.name : 'No Candidates Available';
        document.getElementById('mcAge').textContent = c ? c.age : '-';
        document.getElementById('mcLocation').textContent = c ? c.location : '-';
        document.getElementById('mcGender').textContent = c ? c.gender : '-';
        document.getElementById('mcOccupation').textContent = c ? c.occupation : '-';
        document.getElementById('mcHobbies').textContent = c ? c.hobbies : '-';
        document.getElementById('mcBio').textContent = c ? `"${c.bio}"` : 'No bio set.';
        document.getElementById('mcLookingFor').textContent = c ? `"${c.lookingFor}"` : 'Not specified.';

        const total = hasCandidates ? matchingCandidates.length : 0;
        const current = hasCandidates ? candidateIndex + 1 : 0;
        
        const progressText = document.querySelector('#curatedModalOverlay .progressText');
        if (progressText) {
            progressText.textContent = `${current} of ${total}`;
        }
        
        const fillEl = document.querySelector('#curatedModalOverlay .progressBarFill');
        if (fillEl) {
            fillEl.style.width = total > 0 ? `${(current / total) * 100}%` : '0%';
        }

        renderCandidatePhoto();
    }

    function renderCandidatePhoto() {
        const photoEl = document.getElementById('curatedModalPhoto');
        if (!photoEl) return;

        const c = matchingCandidates[candidateIndex];

        if (c && c.photos && c.photos.length > 0) {
            const currentImg = c.photos[photoIndex] || c.photos[0];
            photoEl.style.backgroundImage = `url('${currentImg}')`;
            photoEl.style.backgroundSize = 'cover';
            photoEl.style.backgroundPosition = 'center';
        } else {
            photoEl.style.backgroundImage = 'none';
            photoEl.style.backgroundColor = singlePhotoColours[candidateIndex % singlePhotoColours.length];
        }
    }

    function nextCuratedPhoto() {
        if (!matchingCandidates || !matchingCandidates.length) return;
        const c = matchingCandidates[candidateIndex];
        if (c && c.photos && c.photos.length > 1) {
            photoIndex = (photoIndex + 1) % c.photos.length;
            renderCandidatePhoto();
        }
    }

    function prevCuratedPhoto() {
        if (!matchingCandidates || !matchingCandidates.length) return;
        const c = matchingCandidates[candidateIndex];
        if (c && c.photos && c.photos.length > 1) {
            photoIndex = (photoIndex - 1 + c.photos.length) % c.photos.length;
            renderCandidatePhoto();
        }
    }

    function nextCuratedProfile() {
        if (!matchingCandidates || !matchingCandidates.length) return;
        candidateIndex = (candidateIndex + 1) % matchingCandidates.length;
        photoIndex = 0;
        renderCandidate();
    }

    function requestVouch() {
        if (!matchingCandidates || !matchingCandidates.length) return;
        const c = matchingCandidates[candidateIndex];
        alert(`Vouch requested for ${c.name || 'Candidate'}!`);
        closeCuratedModal();
    }
</script>