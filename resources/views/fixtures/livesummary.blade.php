@extends('layouts/app')

@section('content')

<div class="heading"> 
    India vs West Indies, 1st ODI - Live Cricket Score
</div>
<div class="subheading">
    <span>Series : West Indies tour of India, 2022 </span>
    <span>Venue :  Narendra Modi Stadium, Ahmedabad</span>
    <span>Date & Time : Feb 06, 01:30 PM LOCAL</span>
</div>
<hr />

<div class="row main-div">
    <div class="live-scorecard">
        <div class="innings-completed-score">WI 176 (43.5)</div>
        <div class="innings-progress-score">IND 98/2 (14.2)  <span>CRR: 6.84 REQ: 2.21</span></div>
        <div class="match-note">India need 79 runs</div>
        <div class="row progress-summary">
            <div class="col-md-8">
                <table class="table batting-table" width="100%">
                    <tr>
                        <th width="40%">Batter</th>
                        <th width="10%">R</th>
                        <th width="10%">B</th>
                        <th width="10%">4s</th>
                        <th width="10%">6s</th>
                        <th width="10%">SR</th>
                    </tr>
                    <tr>
                        <td> <a href="javascript:void(0)"> Ishan Kishan </a> <span>*</span></td>
                        <td>27</td>
                        <td>33</td>
                        <td>2</td>
                        <td>1</td>
                        <td>81.82</td>
                    </tr>
                    <tr>
                        <td> <a href="javascript:void(0)"> Rishab Pant </a> <span>*</span></td>
                        <td>6</td>
                        <td>5</td>
                        <td>1</td>
                        <td>0</td>
                        <td>120</td>
                    </tr>
                </table>
                <table class="table bowling-table" width="100%">
                    <tr>
                        <th width="40%">Bowler</th>
                        <th width="10%">O</th>
                        <th width="10%">M</th>
                        <th width="10%">R</th>
                        <th width="10%">W</th>
                        <th width="10%">ECO</th>
                    </tr>
                    <tr>
                        <td> <a href="javascript:void(0)"> Akeal Hosein </a> <span>*</span></td>
                        <td>3</td>
                        <td>0</td>
                        <td>18</td>
                        <td>0</td>
                        <td>6</td>
                    </tr>
                    <tr>
                        <td> <a href="javascript:void(0)"> Alzarri Joseph </a> <span>*</span></td>
                        <td>5</td>
                        <td>0</td>
                        <td>35</td>
                        <td>2</td>
                        <td>7</td>
                    </tr>
                </table>
            </div>
            <div class="col-md-4">
                <table class="table key-stats" width="100%">
                    <tr>
                        <th>Key Stats</th>
                    </tr>
                    <tr>
                        <td>
                            Partnership: 0(1) <br/>
                            Last Wkt: Ishan Kishan c Fabian Allen b Akeal Hosein 28(36) - 115/3 in 16.4 ov. <br/>
                            Last 5 overs: 39 runs, 3 wkts <br/>
                            Toss: India (Bowling)
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection