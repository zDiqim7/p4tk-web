const scrollProgress = document.querySelector(".scroll-progress");

if (scrollProgress instanceof HTMLElement) {
  const updateScrollProgress = () => {
    const scrollableHeight = document.documentElement.scrollHeight - window.innerHeight;
    const progress = scrollableHeight > 0
      ? Math.min(1, Math.max(0, window.scrollY / scrollableHeight))
      : 0;

    scrollProgress.style.setProperty("--scroll-progress", String(progress));
    scrollProgress.setAttribute("aria-valuenow", String(Math.round(progress * 100)));
  };

  window.addEventListener("scroll", updateScrollProgress, { passive: true });
  window.addEventListener("resize", updateScrollProgress);
  updateScrollProgress();
}
