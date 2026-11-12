/* ইনফো আইটি — সব পেজের কমন স্ক্রিপ্ট (যোগাযোগ ফর্ম ভ্যালিডেশন) */
(function () {
  const bnToEn = s => s.replace(/[০-৯]/g, d => '০১২৩৪৫৬৭৮৯'.indexOf(d));

  // বাংলাদেশি মোবাইল নম্বর যাচাই: 01XXXXXXXXX বা +8801XXXXXXXXX
  function isValidBdPhone(v) {
    const clean = bnToEn(v).replace(/[\s-]/g, '');
    return /^(?:\+?88)?01[3-9]\d{8}$/.test(clean);
  }

  document.querySelectorAll('.js-contact-form').forEach(form => {
    const phone = form.querySelector('[name="phone"]');

    phone.addEventListener('input', () => {
      phone.setCustomValidity(phone.value && !isValidBdPhone(phone.value) ? 'invalid' : '');
      phone.classList.remove('cf-invalid');
    });

    form.querySelectorAll('.cf-control').forEach(el => {
      el.addEventListener('input', () => el.classList.remove('cf-invalid'));
    });

    // ব্রাউজারে আগে যাচাই, ঠিক থাকলে সার্ভারে (Laravel) পাঠানো হয়
    form.addEventListener('submit', e => {
      phone.setCustomValidity(phone.value && !isValidBdPhone(phone.value) ? 'invalid' : '');
      form.classList.add('was-validated');
      if (!form.checkValidity()) {
        e.preventDefault();
        const firstBad = form.querySelector(':invalid');
        if (firstBad) firstBad.focus();
        return;
      }
      const btn = form.querySelector('.cf-submit');
      if (btn) { btn.disabled = true; btn.style.opacity = '.75'; }
    });
  });

  // সার্ভার থেকে আসা সফল বার্তা / এরর থাকলে সেখানে স্ক্রল করে
  const flash = document.querySelector('.cf-alert .alert') || document.querySelector('.cf-control.cf-invalid');
  if (flash) flash.scrollIntoView({ behavior: 'smooth', block: 'center' });
})();
