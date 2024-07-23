<p><b>Hi Admin,</b></p>
<br>
<p>Your Report <b>{{$upload_name}}</b> is ready now.</p>
<br>
<table>
    <thead>
        <tr>
            <th>File Name</th>
            <th>Uploaded On</th>    
            <th>Status</th>    
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>{{$upload_name}}</td>
            <td>{{date('Y-m-d H:i:s',strtotime($created_at))}}</td>
            <td>
                <ul>
                    <li><a href="https://s3.us-east-2.amazonaws.com/files.dncblocker.com/{{$identical_file_path}}">DNC</a></li>
                    <li><a href="https://s3.us-east-2.amazonaws.com/files.dncblocker.com/{{$unidentical_file_path}}">NON DNC</a></li>
                </ul>
            </td>
        </tr>
    </tbody>
</table>

<br>
<p><img src="https://dncscrubbing.com/webtheme/img/logo.png" height="150" width="300"></p>
<style>
/* Center tables for demo */
table {
  margin: 0 auto;
}

/* Default Table Style */
table {
  color: #333;
  background: white;
  border: 1px solid grey;
  font-size: 12pt;
  border-collapse: collapse;
}
table thead th,
table tfoot th {
  color: #777;
  background: rgba(0,0,0,.1);
}
table caption {
  padding:.5em;
}
table th,
table td {
  padding: .5em;
  border: 1px solid lightgrey;
}
/* Zebra Table Style */
[data-table-theme*=zebra] tbody tr:nth-of-type(odd) {
  background: rgba(0,0,0,.05);
}
[data-table-theme*=zebra][data-table-theme*=dark] tbody tr:nth-of-type(odd) {
  background: rgba(255,255,255,.05);
}
/* Dark Style */
[data-table-theme*=dark] {
  color: #ddd;
  background: #333;
  font-size: 12pt;
  border-collapse: collapse;
}
[data-table-theme*=dark] thead th,
[data-table-theme*=dark] tfoot th {
  color: #aaa;
  background: rgba(0255,255,255,.15);
}
[data-table-theme*=dark] caption {
  padding:.5em;
}
[data-table-theme*=dark] th,
[data-table-theme*=dark] td {
  padding: .5em;
  border: 1px solid grey;
}
table{
    width: 100%;
}
</style>