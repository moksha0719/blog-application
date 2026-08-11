<?php include 'includes/header.php'; ?>

<section class="form-section">

    <div class="form-container blog-editor">

        <h2>Create New Blog</h2>

        <form action="#" method="POST">

            <div class="form-group">

                <label for="title">
                    Blog Title
                </label>

                <input
                    type="text"
                    id="title"
                    name="title"
                    placeholder="Enter blog title"
                    required
                >

            </div>


            <div class="form-group">

                <label for="content">
                    Blog Content
                </label>

                <textarea
                    id="content"
                    name="content"
                    rows="12"
                    placeholder="Write your blog here..."
                    required
                ></textarea>

            </div>


            <button type="submit" class="btn">
                Publish Blog
            </button>

        </form>

    </div>

</section>


<?php include 'includes/footer.php'; ?>