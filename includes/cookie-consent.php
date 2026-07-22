<?php
if (!defined('ALLOWED_ACCESS')) {
    header("HTTP/1.1 403 Forbidden");
    exit('Access Denied');
}
?>
<!-- GDPR Cookie Consent Banner -->
<div id="gdpr-cookie-banner" class="cookie-banner-container" style="display: none;">
    <div class="cookie-content">
        <div class="cookie-text-wrap">
            <h5 class="cookie-title">حماية خصوصيتك ومعلوماتك الشخصية</h5>
            <p class="cookie-desc">
                نحن نستخدم ملفات تعريف الارتباط (Cookies) الضرورية لضمان عمل الموقع بكفاءة وتأمين الجلسات (Sessions) وفقاً لقوانين حماية البيانات الأوروبية (GDPR). يمكنك قبول الكوكيز الأساسية أو مراجعة التفاصيل.
                <a href="<?php echo $path_prefix; ?>privacy.php" class="privacy-link">سياسة الخصوصية</a>.
            </p>
        </div>
        <div class="cookie-actions">
            <button type="button" id="accept-all-cookies" class="btn-cookie-accept">قبول الكل</button>
            <button type="button" id="reject-optional-cookies" class="btn-cookie-reject">الضرورية فقط</button>
        </div>
    </div>
</div>

<style>
.cookie-banner-container {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    background: #1a1a1a;
    color: #f8f9fa;
    padding: 20px;
    z-index: 99999;
    box-shadow: 0 -4px 20px rgba(0,0,0,0.25);
    border-top: 3px solid #66aeee;
    font-family: inherit;
    direction: rtl;
    text-align: right;
}
.cookie-content {
    max-width: 1200px;
    margin: 0 auto;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 15px;
}
.cookie-text-wrap {
    flex: 1;
    min-width: 280px;
}
.cookie-title {
    font-size: 1.1rem;
    font-weight: 700;
    margin-bottom: 5px;
    color: #fff;
}
.cookie-desc {
    font-size: 0.9rem;
    margin: 0;
    color: #cbd5e1;
    line-height: 1.5;
}
.privacy-link {
    color: #66aeee;
    text-decoration: underline;
    margin-right: 5px;
}
.cookie-actions {
    display: flex;
    gap: 10px;
}
.btn-cookie-accept, .btn-cookie-reject {
    padding: 10px 20px;
    border-radius: 6px;
    font-weight: 600;
    font-size: 0.9rem;
    cursor: pointer;
    transition: all 0.2s ease;
    border: none;
}
.btn-cookie-accept {
    background: #66aeee;
    color: #fff;
}
.btn-cookie-accept:hover {
    background: #509ad7;
}
.btn-cookie-reject {
    background: #334155;
    color: #e2e8f0;
}
.btn-cookie-reject:hover {
    background: #475569;
}
</style>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const consentStatus = localStorage.getItem('gdpr_consent_status');
    const banner = document.getElementById('gdpr-cookie-banner');
    
    if (!consentStatus) {
        banner.style.display = 'block';
    }

    document.getElementById('accept-all-cookies').addEventListener('click', function() {
        localStorage.setItem('gdpr_consent_status', 'accepted_all');
        localStorage.setItem('gdpr_consent_timestamp', new Date().toISOString());
        banner.style.display = 'none';
        // هنا يمكنك تفعيل أدوات التحليل أو التتبع إن وجدت
    });

    document.getElementById('reject-optional-cookies').addEventListener('click', function() {
        localStorage.setItem('gdpr_consent_status', 'essential_only');
        localStorage.setItem('gdpr_consent_timestamp', new Date().toISOString());
        banner.style.display = 'none';
        // يتم الاكتفاء بالكوكيز الضرورية للجلسات فقط
    });
});
</script>
