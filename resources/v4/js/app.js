import Alpine from "alpinejs";

window.Alpine = Alpine;
document.addEventListener("alpine:init", () => {
    Alpine.data("globals", () => ({
        init() {
            window.addEventListener("resize", () => {
                if (window.innerWidth >= 768) {
                    this.open_comment_responsive = false;
                }
            });
            if (window.innerWidth >= 768) {
            document
                .getElementById("content")
                .addEventListener("scroll", (e) => {
                    let backToTop = document.getElementById("back-to-top");
                    if (e.target.scrollTop > 40) {
                        backToTop.classList.remove("hidden");
                        backToTop.classList.add("flex");
                    } else if (!backToTop.classList.contains("hidden")) {
                        backToTop.classList.remove("flex");
                        backToTop.classList.add("hidden");
                    }
                });
            }
        },
        open_sidebar: false,
        open_search_responsive: false,
        open_comment_responsive: false,
        open_report:false,

        backToTop() {
            document.getElementById("content").scrollTop = 0;
        },
    }));
    Alpine.data("searchs", () => ({
        search: "",
        searchs: [
            {
                name: "Ozella Heaney",
                content:
                    "Nihil repellat ab quia reiciendis et. Eum odio vero aut. Nemo magnam quia optio officiis distinctio exercitationem eligendi id. Cumque aut aut assumenda et veritatis.",
            },
        ],
        get filterSearchs() {
            if (this.search)
                return this.searchs.filter((i) =>
                    i.name.startsWith(this.search)
                );
        },
        get noResults() {
            return this.search && !this.filterSearchs.length;
        },
    }));
});
Alpine.start();
