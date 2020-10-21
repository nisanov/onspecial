import FloatingLabel from "./plugins/FloatingLabel";
import Notify from "./plugins/Notify";

FloatingLabel.initialize();

const fab = document.querySelector(".fab button");
if (fab !== null) {
    fab.addEventListener("click", event => event.target.closest('.fab').classList.toggle('activated'));
}
