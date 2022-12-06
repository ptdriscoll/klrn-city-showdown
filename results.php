    <?php
    if(!ISSET($home)) {
        header('location: ./'); 
        die(); 
    }
    ?>
    <div id="results">
        <table>
          <tr>
            <th id="district">Dist</th>
            <th id="contestant">Contestant</th>
            <th id="votes">Votes</th>
          </tr>
    <?php
    require('conn.php');
    $contestants_arr = include('contestants.php');
    $sql = 'SELECT to_district, COUNT(*) AS count FROM votes 
    GROUP BY to_district ORDER By to_district';
    $result = $conn->query($sql);

    //get data, and echo to_district, contestants_arr[to_district] and count  
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo("<tr>
                    <td>{$row['to_district']}</td>
                    <td>{$contestants_arr[$row['to_district']]}</td>
                    <td>{$row['count']}</td>
                  </tr>");
        }
      } else { //no data to show
        echo('<tr>
                <td class="error">No data</td>
                <td class="error">No data</td>
                <td class="error">No data</td>
              </tr>');
      }

    $conn->close();    
    ?>
        </table>
      </div>