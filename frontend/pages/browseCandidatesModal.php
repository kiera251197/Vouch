<?php
    $pendingCandidates = $pendingCandidates ?? [];
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
    let pendingCandidates = <?= json_encode($pendingCandidates) ?>;
    let candidateIndex = 0;

    function showAlert(message, title = 'NOTICE') {
        document.getElementById('customAlertTitle').textContent = title;
        document.getElementById('customAlertMessage').textContent = message;
        document.getElementById('customAlertOverlay').classList.add('active');
    }

    function closeCustomAlert() {
        document.getElementById('customAlertOverlay').classList.remove('active');
    }

    function openCandidateModal() {
        candidateIndex = 0;
        renderCandidate();
        document.getElementById('candidateModalOverlay').classList.add('active');
    }

    function closeCandidateModal() {
        document.getElementById('candidateModalOverlay').classList.remove('active');
    }

    function renderCandidate() {
        const photoEl = document.getElementById('candidateModalPhoto');
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

        if (c.photo) {
            photoEl.style.backgroundImage = `url('${c.photo}')`;
            photoEl.style.backgroundSize = 'cover';
            photoEl.style.backgroundPosition = 'center';
        } else {
            photoEl.style.backgroundImage = 'none';
            photoEl.style.backgroundColor = candidateColorPool[candidateIndex % candidateColorPool.length];
        }

        document.getElementById('candidateProgressText').textContent = `${candidateIndex + 1} of ${total}`;
        document.getElementById('candidateProgressFill').style.width = `${((candidateIndex + 1) / total) * 100}%`;
    }

    function nextCandidatePhoto() {
        if (pendingCandidates.length === 0) return;
        candidateIndex = (candidateIndex + 1) % pendingCandidates.length;
        renderCandidate();
    }

    function prevCandidatePhoto() {
        if (pendingCandidates.length === 0) return;
        candidateIndex = (candidateIndex - 1 + pendingCandidates.length) % pendingCandidates.length;
        renderCandidate();
    }

    function reviewCurrentCandidate(action) {
        if (pendingCandidates.length === 0) return;

        const c = pendingCandidates[candidateIndex];
        const formData = new FormData();
        formData.append('vouching_id', c.vouching_id);
        formData.append('action', action);

        fetch('../../backend/routes/vouchAction.php', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                pendingCandidates.splice(candidateIndex, 1);
                if (candidateIndex >= pendingCandidates.length) {
                    candidateIndex = 0;
                }
                if (pendingCandidates.length === 0) {
                    renderCandidate();
                    closeCandidateModal();
                    location.reload();
                } else {
                    renderCandidate();
                }
            } else {
                showAlert('Something went wrong saving that decision. Please try again', 'ERROR');
            }
        })
        .catch(err => {
            console.error('Error reviewing candidate:', err);
            showAlert('Network error please try again.', 'CONNECTION ERROR');
        });
    }

</script>