<?php
    include("includes/header.php");
?>

<div class="flex items-center justify-between mb-5">
    <h1 class="text-white text-2xl font-medium">Jobs</h1>
    <a href="add-job.php"><i class="fa-solid fa-plus text-white cursor-pointer hover:text-neutral-400 transition"></i></a>
</div>

<div class="flex flex-col bg-neutral-800 rounded-lg text-white">
  <div class="overflow-x-auto">
    <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8">
      <div class="overflow-hidden">
        <?php
            $jobs = getJobs();

            if(mysqli_num_rows($jobs) > 0) {
            ?>    

            <table class="min-w-full text-left text-sm font-light">
                <thead class="border-b font-medium border-neutral-600">
                    <tr>
                        <th scope="col" class="px-6 py-4">ID</th>
                        <th scope="col" class="px-6 py-4">Job Title</th>
                        <th scope="col" class="px-6 py-4">Company</th>
                        <th scope="col" class="px-6 py-4">Package</th>
                        <th scope="col" class="px-6 py-4">Location</th>
                        <th scope="col" class="px-6 py-4">SSC %</th>
                        <th scope="col" class="px-6 py-4">HSC/ Diploma %</th>
                        <th scope="col" class="px-6 py-4">Current %</th>
                        <th scope="col" class="px-6 py-4">Edit</th>
                        <th scope="col" class="px-6 py-4">Delete</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        foreach($jobs as $job) {
                        ?>
                            <tr class="border-b border-neutral-600">
                                <td class="whitespace-nowrap px-6 py-4 font-medium"><?= $job['id']?></td>
                                <td class="whitespace-nowrap px-6 py-4"><?= $job['title']?></td>
                                <td class="whitespace-nowrap px-6 py-4"><?= $job['company_name']?></td>
                                <td class="whitespace-nowrap px-6 py-4"><?= $job['package']?> LPA</td>
                                <td class="whitespace-nowrap px-6 py-4"><?= $job['location']?></td>
                                <td class="whitespace-nowrap px-6 py-4"><?= $job['ssc_grade']?></td>
                                <td class="whitespace-nowrap px-6 py-4"><?= $job['hsc_or_diploma_grade']?></td>
                                <td class="whitespace-nowrap px-6 py-4"><?= $job['current_grade']?></td>
                                <td class="whitespace-nowrap px-6 py-4"><a href="edit-job.php?id=<?= $job['id'];?>"><i class="fa-solid fa-pen-to-square"></i></a></td>
                                <td class="whitespace-nowrap px-6 py-4"><i class="fa-solid fa-trash"></i></td>
                            </tr>
                        <?php
                        }
                    ?>
                </tbody>
            </table>

            <?php
            }
            else {
            ?>
                <p>No records found</p>
            <?php
            }
        ?>
      </div>
    </div>
  </div>
</div>

<?php
    include("includes/footer.php");
?>