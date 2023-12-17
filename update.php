<?php
session_start();

$dbHost = 'localhost';
$dbUsername = 'root';
$dbPassword = '';
$dbName = 'db_absen_jumat';

$conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$userId = $_SESSION['user_id'];

$userRayonQuery = "SELECT rayon FROM users WHERE id = '$userId'";
$userRayonResult = $conn->query($userRayonQuery);

$userLevelQuery = "SELECT level FROM users WHERE id = '$userId'";
$userLevelResult = $conn->query($userLevelQuery);

$userLevel = null;
$userRayon = null;

if ($userLevelResult->num_rows === 1) {
    $userRow = $userLevelResult->fetch_assoc();
    $userLevel = $userRow['level'];
}

if ($userRayonResult->num_rows === 1) {
    $userRow = $userRayonResult->fetch_assoc();
    $userRayon = $userRow['rayon'];

    if ($userRayon !== 'Kesis') {
        $sql = "SELECT * FROM siswa WHERE rayon = '$userRayon'";
    } else {
        $sql = "SELECT * FROM siswa";
    }

    $result = $conn->query($sql);
}

if ($userRayon !== 'Kesis') {
    $rekapSql = "SELECT * FROM rekap WHERE rayon = '$userRayon'";
} else {
    $rekapSql = "SELECT * FROM rekap";
}
$rekapResult = $conn->query($rekapSql);
$rekapResult1 = $conn->query($rekapSql);
$rekapResult2 = $conn->query($rekapSql);

if (isset($_POST['search_button'])) {
    $searchDate = $_POST['search_date'];
    $userRayon = $_SESSION['rayon'];

    $stmt = $conn->prepare("SELECT * FROM rekap WHERE tanggal = ? AND rayon = ?");

    if ($stmt) {
        $stmt->bind_param("ss", $searchDate, $userRayon);
        if ($stmt->execute()) {
            $rekapResult = $stmt->get_result();
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Error: " . $conn->error;
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.8.1/flowbite.min.css" rel="stylesheet" />
</head>
<body>
    
<button data-drawer-target="default-sidebar" data-drawer-toggle="default-sidebar" aria-controls="default-sidebar" type="button" class="inline-flex items-center p-2 mt-2 ml-3 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600">
    <span class="sr-only">Open sidebar</span>
    <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
       <path clip-rule="evenodd" fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path>
    </svg>
 </button>
 
 <aside id="default-sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen transition-transform -translate-x-full sm:translate-x-0" aria-label="Sidebar">
    <div class="h-full px-3 py-4 overflow-y-auto bg-gray-50 dark:bg-gray-800">
       <ul class="space-y-2 font-medium">
          <li>
             <a href="dashboard.php" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 17 14">
                    <path d="M16 2H1a1 1 0 0 1 0-2h15a1 1 0 1 1 0 2Zm0 6H1a1 1 0 0 1 0-2h15a1 1 0 1 1 0 2Zm0 6H1a1 1 0 0 1 0-2h15a1 1 0 0 1 0 2Z"/>
                </svg>
                <span class="ml-3">Dashboard</span>
             </a>
          </li>
          <?php if($userLevel == 3 || $userLevel == 2) { ?>
          <li>
            <a href="update.php" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 16">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m2.707 14.293 5.586-5.586a1 1 0 0 0 0-1.414L2.707 1.707A1 1 0 0 0 1 2.414v11.172a1 1 0 0 0 1.707.707Z"/>
                </svg>
               <span class="flex-1 ml-3 whitespace-nowrap">Update</span>
            </a>
         </li>
          <?php } ?>
          <li>
            <a href="logout.php" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
               <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 16">
                   <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h11m0 0-4-4m4 4-4 4m-5 3H3a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h3"/>
               </svg>
               <span class="flex-1 ml-3 whitespace-nowrap">Log Out</span>
            </a>
         </li>
       </ul>
    </div>
 </aside>
 
 <div class="p-4 sm:ml-64">
    <div class="p-4 border-2 border-gray-200 border-dashed rounded-lg dark:border-gray-700">
       <div class="w-full">
       <table class="w-full text-s text-left text-gray-600 dark:text-gray-400 table auto">
        <thead class="text-sm text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-300">
        <tr>
            <th scope="col" class="px-6 py-3">
                Name
            </th>
            <th scope="col" class="px-6 py-3">
                NIS
            </th>
            <th scope="col" class="px-6 py-3">
                Rayon
            </th>
            <th scope="col" class="px-6 py-3">
                Rombel
            </th>
            <th scope="col" class="px-6 py-3">
                Kehadiran
            </th>
        </tr>
        </thead>
        <tbody>
        <?php
    echo '<form action="updateFunc.php" method="POST">';
    while ($row = $result->fetch_assoc()) {
        $tanggalQuery = "SELECT tanggal FROM rekap WHERE nis = ?";
        $stmt = $conn->prepare($tanggalQuery);
        $stmt->bind_param("i", $row['nis']);
        $stmt->execute();
        $tanggalResult = $stmt->get_result();

        if ($tanggalResult->num_rows === 1) {
            $tanggalRow = $tanggalResult->fetch_assoc();
            $tanggal = $tanggalRow['tanggal'];
        } else {
            $tanggal = "";
        }

        echo '<tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">';
        echo '<td class="px-6 py-4"><input type="text" name="nama[' . $row['nis'] . ']" id="floating_password" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" value="' . $row['nama'] . '"/></td>';
        echo '<td class="px-6 py-4"><input type="number" name="nis[' . $row['nis'] . ']" id="floating_password" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" value="' . $row['nis'] . '"/></td>';
        echo '<td class="px-6 py-4"><input type="text" name="rombel[' . $row['nis'] . ']" id="floating_password" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" value="' . $row['rombel'] . '"/></td>';
        echo '<td class="px-6 py-4"><input type="text" name="rayon[' . $row['nis'] . ']" id="floating_password" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" value="' . $row['rayon'] . '"/></td>';
        echo '<td class="px-6 py-4">';
        echo '<div class="flex items-center h-5">';

        $hadirChecked = ($row['status'] === 'Hadir') ? 'checked' : '';
        $tidakHadirChecked = ($row['status'] === 'Tidak Hadir') ? 'checked' : '';

        echo '<input type="radio" name="status[' . $row['nis'] . ']" value="Hadir" class="w-4 mr-2 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800" required ' . $hadirChecked . '> Hadir';
        echo '<input type="radio" name="status[' . $row['nis'] . ']" value="Tidak Hadir" class="w-4 ml-5 mr-2 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800" required ' . $tidakHadirChecked . '> Tidak Hadir';
        echo '</div>';
        echo '</td>';
        echo '</tr>';
    }
    ?>
    <div class="inline-flex">
        <div class="inline-flex rounded-md shadow-sm" role="group">
            <input type="submit" class="px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-l-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-blue-500 dark:focus:text-white" value="Update">
        </div>
    </div>
        </tbody>
    </table>
    </form>
    </tbody>
</table>
<?php
if (isset($_SESSION['success_message'])) {
    echo '
<div id="alert-1" class="flex items-center p-4 mb-4 text-blue-800 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400" role="alert">
  <svg class="flex-shrink-0 w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
    <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
  </svg>
  <span class="sr-only">Info</span>
  <div class="ml-3 text-sm font-medium">
    Data berhasil di Update!
  </div>
    <button type="button" class="ml-auto -mx-1.5 -my-1.5 bg-blue-50 text-blue-500 rounded-lg focus:ring-2 focus:ring-blue-400 p-1.5 hover:bg-blue-200 inline-flex items-center justify-center h-8 w-8 dark:bg-gray-800 dark:text-blue-400 dark:hover:bg-gray-700" data-dismiss-target="#alert-1" aria-label="Close">
      <span class="sr-only">Close</span>
      <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
      </svg>
  </button>
</div>';
    unset($_SESSION['success_message']);
}
?>
       </div>
    </div>
 </div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.8.1/flowbite.min.js"></script>
</body>
</html>
