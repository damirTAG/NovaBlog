// Liking Post
export function toggleLike(postUuid, isLike) {
    fetch(`/p/${postUuid}/like`, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-Requested-With": "XMLHttpRequest",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
                .content,
        },
        body: JSON.stringify({ is_like: isLike }),
    })
        .then((response) => response.json())
        .then((data) => {
            console.log("Response:", data);
            if (data.success) {
                document.getElementById(`likes-count-${postUuid}`).textContent =
                    data.likes_count;
                document.getElementById(
                    `dislikes-count-${postUuid}`
                ).textContent = data.dislikes_count;

                const likeBtn = document.getElementById(`like-btn-${postUuid}`);
                const dislikeBtn = document.getElementById(
                    `dislike-btn-${postUuid}`
                );

                if (isLike) {
                    likeBtn.classList.remove("btn-outline-primary");
                    likeBtn.classList.add("btn-primary");

                    dislikeBtn.classList.remove("btn-danger");
                    dislikeBtn.classList.add("btn-outline-danger");
                } else {
                    dislikeBtn.classList.remove("btn-outline-danger");
                    dislikeBtn.classList.add("btn-danger");

                    likeBtn.classList.remove("btn-primary");
                    likeBtn.classList.add("btn-outline-primary");
                }
            }
        })
        .catch((error) => {
            console.error("AJAX Like Error:", error);
        });
}
