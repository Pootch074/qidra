document.addEventListener("DOMContentLoaded", () => {
    function fetchSteps() {
        fetch(window.appRoutes.steps)
            .then((res) => res.json())
            .then((data) => {
                const container = document.getElementById("stepsContainer");
                const noSteps = document.getElementById("noSteps");
                if (!container || !noSteps) return;

                container.innerHTML = "";
                if (!data || !data.length) {
                    noSteps.classList.remove("hidden");
                    return;
                }
                noSteps.classList.add("hidden");

                data.forEach((step) => {
                    const normalizedName = step.step_name?.toLowerCase();
                    if (normalizedName === "release") return;

                    if (
                        normalizedName === "assessment" &&
                        window.appUser.assignedCategory.toLowerCase() ===
                            "regular"
                    ) {
                        return;
                    }

                    const card = document.createElement("div");
                    card.className =
                        "rounded-lg shadow-md p-1 mb-3 flex flex-col bg-gray-200";

                    const stepNameDisplay =
                        step.step_name && step.step_name !== "None"
                            ? step.step_name
                            : "";

                    let html = `
                        <h3 class="text-4xl font-bold text-[#000000] mb-1 py-3 flex items-center justify-center space-x-2 bg-white rounded">
                            <span>STEP ${step.step_number}</span>
                            ${
                                stepNameDisplay
                                    ? `<span>${stepNameDisplay}</span>`
                                    : ""
                            }
                        </h3>
                    `;

                    if (step.windows.length > 0) {
                        html += `<div class="grid grid-cols-2 gap-2">`;

                        step.windows.forEach((win) => {
                            const firstTx =
                                win.transactions?.length > 0
                                    ? win.transactions[0]
                                    : null;

                            let bgClass = "bg-[#2e3192]";

                            if (
                                (normalizedName === "pre-assessment" ||
                                    normalizedName === "encode") &&
                                window.appUser.assignedCategory.toLowerCase() ===
                                    "priority"
                            ) {
                                bgClass = "bg-red-600";
                            }

                            html += `
                                <div class="rounded-lg text-[#FFFFFF] text-2xl font-semibold flex flex-col items-center justify-center w-full">
                                    <div class="flex items-center w-full h-full rounded-lg border-4 border-[#2e3192]">
                                        <span class="${bgClass} py-1 text-center w-1/5">
                                            <p class="text-xl font-semibold">Window</p>
                                            <p class="text-5xl font-bold">${
                                                win.window_number
                                            }</p>
                                        </span>
                                        ${
                                            firstTx
                                                ? `<span class="queue-number flex items-center justify-center bg-[#FFFFFF] text-[#000000] px-3 py-1 text-6xl font-bold text-center w-4/5 h-full rounded-r-lg" data-queue="${firstTx.queue_number}">
                                                    ${firstTx.queue_number}
                                                  </span>`
                                                : `<span class="flex items-center justify-center bg-[#FFFFFF] text-[#000000] px-3 py-1 text-sm text-center w-4/5 h-full rounded-r-lg">🚫</span>`
                                        }
                                    </div>
                                </div>`;
                        });

                        html += `</div>`;
                    } else {
                        html += `<p class="text-gray-400 italic text-sm">No windows assigned</p>`;
                    }

                    card.innerHTML = html;
                    container.appendChild(card);
                });
            })
            .catch((err) => {
                console.error("Error fetching steps:", err);
                const noSteps = document.getElementById("noSteps");
                if (noSteps) {
                    noSteps.textContent = "Error loading steps.";
                    noSteps.classList.remove("hidden");
                }
            });
    }

    fetchSteps();
    // fetchLatestTransaction();
    // setInterval(fetchSteps, 1000);
    // setInterval(fetchLatestTransaction, 1000);
});
