<div class="modalOverlay" id="historyProfileModalOverlay">
    <div class="modalOuterContainer">
        <div class="modalCard">

            <button type="button" class="modalCloseBtn" onclick="closeHistoryProfileModal()">
                <img src="../assets/images/pinkLogo.png" alt="Logo" class="modalLogoIcon">
            </button>

            <div class="modalPhotoSide">
                <div class="modalPhotoFiller" id="historyProfileModalPhoto">
                    <div class="photoGradientOverlay"></div>
                    <div class="photoNavRow">
                        <button type="button" class="photoNavBtn" onclick="prevHistoryPhoto()">
                            <img src="../assets/images/arrowIcon.png" alt="Arrow Icon" class="arrowIcon" style="width: 24px; height: auto; margin-right: 8px;">
                            Previous Image
                        </button>
                        <button type="button" class="photoNavBtn" onclick="nextHistoryPhoto()">
                            Next Image
                            <img src="../assets/images/arrowIcon.png" alt="Arrow Icon" class="arrowIcon" style="transform: rotate(180deg); width: 24px; height: auto; margin-left: 8px;">
                        </button>
                    </div>
                </div>
            </div>

            <div class="modalDetailsSide">
                <div class="modalHeaderRow">
                    <div class="cardTitle">
                        <span id="hpNameTitle">-</span>'S PROFILE
                    </div>
                </div>

                <div class="detailRow">
                    <div class="detailCol">
                        <div class="detailLabel">NAME</div>
                        <div class="detailValue" id="hpName">-</div>
                    </div>
                    <div class="detailCol">
                        <div class="detailLabel">AGE</div>
                        <div class="detailValue" id="hpAge">-</div>
                    </div>
                </div>

                <div class="detailRow">
                    <div class="detailCol">
                        <div class="detailLabel">LOCATION</div>
                        <div class="detailValue" id="hpLocation">-</div>
                    </div>
                    <div class="detailCol">
                        <div class="detailLabel">GENDER</div>
                        <div class="detailValue" id="hpGender">-</div>
                    </div>
                </div>

                <div class="detailRow">
                    <div class="detailCol">
                        <div class="detailLabel">OCCUPATION</div>
                        <div class="detailValue" id="hpOccupation">-</div>
                    </div>
                    <div class="detailCol">
                        <div class="detailLabel">HOBBIES</div>
                        <div class="detailValue" id="hpHobbies">-</div>
                    </div>
                </div>

                <div class="detailBlock">
                    <div class="detailLabel">BIO</div>
                    <div class="detailQuote" id="hpBio">-</div>
                </div>

                <div class="detailBlock">
                    <div class="detailLabel">LOOKING FOR</div>
                    <div class="detailQuote" id="hpLookingFor">-</div>
                </div>

            </div>

        </div>
    </div>
</div>

<script>
    let historyPhotoIndex = 0;
    let currentHistoryPhotos = [];

    function openHistoryProfileModal(i) {
        const c = vouchHistoryData[i];
        if (!c) return;

        historyPhotoIndex = 0;
        currentHistoryPhotos = c.photos || [];

        document.getElementById('hpNameTitle').textContent = c.name ? c.name.split(' ')[0].toUpperCase() : '';
        document.getElementById('hpName').textContent = c.name;
        document.getElementById('hpAge').textContent = c.age;
        document.getElementById('hpLocation').textContent = c.location;
        document.getElementById('hpGender').textContent = c.gender;
        document.getElementById('hpOccupation').textContent = c.occupation;
        document.getElementById('hpHobbies').textContent = c.hobbies;
        document.getElementById('hpBio').textContent = c.bio;
        document.getElementById('hpLookingFor').textContent = c.lookingFor;

        renderHistoryPhoto();
        document.getElementById('historyProfileModalOverlay').classList.add('active');
    }

    function closeHistoryProfileModal() {
        document.getElementById('historyProfileModalOverlay').classList.remove('active');
    }

    function renderHistoryPhoto() {
        const photoEl = document.getElementById('historyProfileModalPhoto');
        if (!photoEl) return;

        if (currentHistoryPhotos.length > 0) {
            photoEl.style.backgroundImage = `url('${currentHistoryPhotos[historyPhotoIndex]}')`;
            photoEl.style.backgroundSize = 'cover';
            photoEl.style.backgroundPosition = 'center';
        } else {
            photoEl.style.backgroundImage = 'none';
            photoEl.style.backgroundColor = 'var(--dragonfruit)';
        }
    }

    function nextHistoryPhoto() {
        if (!currentHistoryPhotos.length) return;
        historyPhotoIndex = (historyPhotoIndex + 1) % currentHistoryPhotos.length;
        renderHistoryPhoto();
    }

    function prevHistoryPhoto() {
        if (!currentHistoryPhotos.length) return;
        historyPhotoIndex = (historyPhotoIndex - 1 + currentHistoryPhotos.length) % currentHistoryPhotos.length;
        renderHistoryPhoto();
    }
</script>