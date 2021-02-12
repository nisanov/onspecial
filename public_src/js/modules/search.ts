export default class Search {
  static containers: NodeListOf<Element> = document.querySelectorAll(".pagination .search");
  static initialize = () => {
    if (Search.containers.length !== 0) {
      Search.containers.forEach((container: HTMLDivElement) => {
        const searchInput: HTMLInputElement = container.querySelector("input") as HTMLInputElement;
        searchInput.addEventListener("keypress", (event: KeyboardEvent) => {
          if (event.key === 'Enter') {
            searchButton.click();
          }
        });
        const resetButton: HTMLAnchorElement = container.querySelector(".btn-reset") as HTMLAnchorElement;
        resetButton.addEventListener("click", (event: MouseEvent) => {
          if (searchInput.value.trim() === "") {
            event.preventDefault();
            return false;
          }
          return true;
        });
        const searchButton: HTMLAnchorElement = container.querySelector(".btn-search") as HTMLAnchorElement;
        searchButton.addEventListener("click", (event: MouseEvent) => {
          if (searchInput.value.trim() === "") {
            event.preventDefault();
            searchInput.focus();
            return false;
          }
          searchButton.href += "?search=" + encodeURIComponent(searchInput.value.trim());
          return true;
        });
      });
    }
  }
}
