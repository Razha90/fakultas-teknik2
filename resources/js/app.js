
import NProgress from 'nprogress'
import 'nprogress/nprogress.css'

NProgress.configure({ showSpinner: false,trickle: false })

// window.onbeforeunload = function () {
//   window.scrollTo(0, 0);
// }

// Fungsi untuk berpindah halaman
window.goToPage = async function (url) {
  document.body.classList.remove('nprogress-reading');
  document.body.classList.add('nprogress-page');
  NProgress.set(0);
  NProgress.start();
  window.location.href = url;
  setTimeout(() => {
    NProgress.done();
  }, 500);
}

// Reading progress logic
window.addEventListener('scroll', () => {
  const scrollTop = window.scrollY;
  const docHeight = document.body.scrollHeight - window.innerHeight;
  let progress = scrollTop / docHeight;

  if (progress === 1) progress = 0.999999;

  if (
    !document.body.classList.contains('nprogress-reading') &&
    !document.body.classList.contains('nprogress-page')
  ) {
    document.body.classList.add('nprogress-reading');
    NProgress.start();
  }

  if (scrollTop < 100) {
    NProgress.done();
    document.body.classList.remove('nprogress-reading');
  } else {
    NProgress.set(progress);
  }
  
});

