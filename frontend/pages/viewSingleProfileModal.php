<?php
    $mySingle = $mySingle ?? [];
?>

<div class="modalOverlay" id="singleProfileModalOverlay">
    <div class="modalOuterContainer">
        <div class="modalCard">

            <button type="button" class="modalCloseBtn" onclick="closeSingleProfileModal()">
                <img src="../assets/images/pinkLogo.png" alt="Logo" class="modalLogoIcon">
            </button>

            <div class="modalPhotoSide">
                <div class="modalPhotoFiller" id="singleProfileModalPhoto">
                    <div class="photoGradientOverlay"></div>
                    <div class="photoNavRow">
                        <button type="button" class="photoNavBtn" onclick="prevSinglePhoto()">
                            <img src="../assets/images/arrowIcon.png" alt="Arrow Icon" class="arrowIcon" style="width: 24px; height: auto; margin-right: 8px;">
                            Previous Image
                        </button>
                        <button type="button" class="photoNavBtn" onclick="nextSinglePhoto()">
                            Next Image
                            <img src="../assets/images/arrowIcon.png" alt="Arrow Icon" class="arrowIcon" style="transform: rotate(180deg); width: 24px; height: auto; margin-left: 8px;">
                        </button>
                    </div>
                </div>
            </div>

            <div class="modalDetailsSide">
                <div class="modalHeaderRow">
                    <div class="cardTitle">MY SINGLE'S PROFILE</div>
                </div>

                <div class="detailRow">
                    <div class="detailCol">
                        <div class="detailLabel">NAME</div>
                        <div class="detailValue"><?= htmlspecialchars($mySingle['name']) ?></div>
                    </div>
                    <div class="detailCol">
                        <div class="detailLabel">AGE</div>
                        <div class="detailValue"><?= htmlspecialchars($mySingle['age']) ?></div>
                    </div>
                </div>

                <div class="detailRow">
                    <div class="detailCol">
                        <div class="detailLabel">LOCATION</div>
                        <div class="detailValue"><?= htmlspecialchars($mySingle['location']) ?></div>
                    </div>
                    <div class="detailCol">
                        <div class="detailLabel">GENDER</div>
                        <div class="detailValue"><?= htmlspecialchars($mySingle['gender']) ?></div>
                    </div>
                </div>

                <div class="detailRow">
                    <div class="detailCol">
                        <div class="detailLabel">OCCUPATION</div>
                        <div class="detailValue"><?= htmlspecialchars($mySingle['occupation']) ?></div>
                    </div>
                    <div class="detailCol">
                        <div class="detailLabel">HOBBIES</div>
                        <div class="detailValue"><?= htmlspecialchars($mySingle['hobbies']) ?></div>
                    </div>
                </div>

                <div class="detailBlock">
                    <div class="detailLabel">BIO</div>
                    <div class="detailQuote">"<?= htmlspecialchars($mySingle['bio']) ?>"</div>
                </div>

                <div class="detailBlock">
                    <div class="detailLabel">LOOKING FOR</div>
                    <div class="detailQuote">"<?= htmlspecialchars($mySingle['lookingFor']) ?>"</div>
                </div>

            </div>

        </div>
    </div>
</div>

<script>
    const singleProfilePhotos = <?= json_encode($mySingle['photos'] ?? []) ?>;
    let singlePhotoIndex = 0;

    function openSingleProfileModal() {
        singlePhotoIndex = 0;
        renderSinglePhoto();
        document.getElementById('singleProfileModalOverlay').classList.add('active');
    }

    function closeSingleProfileModal() {
        document.getElementById('singleProfileModalOverlay').classList.remove('active');
    }

    function renderSinglePhoto() {
        const photoEl = document.getElementById('singleProfileModalPhoto');
        if (!photoEl) return;

        if (singleProfilePhotos.length > 0) {
            photoEl.style.backgroundImage = `url('${singleProfilePhotos[singlePhotoIndex]}')`;
            photoEl.style.backgroundSize = 'cover';
            photoEl.style.backgroundPosition = 'center';
        } else {
            photoEl.style.backgroundImage = 'none';
            photoEl.style.backgroundColor = 'var(--petal)';
        }
    }

    function nextSinglePhoto() {
        if (!singleProfilePhotos.length) return;
        singlePhotoIndex = (singlePhotoIndex + 1) % singleProfilePhotos.length;
        renderSinglePhoto();
    }

    function prevSinglePhoto() {
        if (!singleProfilePhotos.length) return;
        singlePhotoIndex = (singlePhotoIndex - 1 + singleProfilePhotos.length) % singleProfilePhotos.length;
        renderSinglePhoto();
    }
</script>