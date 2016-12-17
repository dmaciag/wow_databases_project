<!DOCTYPE html>
<html ng-app="db_app" lang="en">
  <head>
    <meta charset="utf-8"> 
    <title>WoW User DB</title>
    <script src="./node_modules/angular/angular.js"></script>
    <link href="./bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="./css/main.css" rel="stylesheet" type="text/css">
  </head>
  <body ng-controller="bodyCtrl" ng-cloak>
    <div class="container">
      <div class="nav">
        <span class="btn" id="home" ng-click="resetAllDivs()">Home</span>
        <span class="btn" id="classes" ng-click="toggleClasses()" ng-style="{color: showClasses ? 'red' : 'lightgreen'}">Classes</span>
        <span class="btn" id="races" ng-click="toggleRaces()" ng-style="{color: showRaces ? 'red' : 'lightgreen'}">Races</span>
        <span class="btn" id="details" ng-click="toggleDetails()" ng-style="{color: showDetails ? 'red' : 'lightgreen'}">Details</span>
        <span class="searchlimit">
          <span style="vertical-align: middle;">Search Limit:</span>
          <span style="vertical-align: middle;">
            <input id="searchlimit" ng-model="querySearchLimit" ng-keyup="submit_search()"></input>
          </span>
        </span>
        <span id="searchlimit">
          <input ng-keyup="submit_search()" id="searchInput" type="text" placeholder="Search" ng-model="search_query" style="vertical-align: middle;"></input>
        </span>
      </div>
      <div>
        <div class="classes" ng-if="showClasses" style="max-width: 150px; display: inline-block;">
          <table class="table table-condensed table-bordered">
            <tbody>
              <tr>
                <td colspan="2" style="text-align: center;">
                  Classes
                </td>
              </tr>
              <tr ng-repeat="(class, url) in classesUrls">
                <td><img ng-src="{{url}}" alt height="22" width="22"></td>
                <td style="text-align: center;">{{class}}</td>
              </tr>
            </tbody>
          </table>
        </div>
         <div class="races" ng-if="showRaces" style="max-width: 150px; display: inline-block;">
          <table class="table table-condensed table-bordered">
            <tbody>
              <tr>
                <td colspan="3" style="text-align: center;">
                  Races
                </td>
              </tr>
              <tr>
                <td style="text-align: center;">M</td>
                <td style="text-align: center;">F</td>
                <td></td>
              </tr>
              <tr ng-repeat="(race, url) in raceGender[0]">
                <td><img ng-src="{{raceGender[0][race]}}" alt height="22" width="22"></td>
                <td><img ng-src="{{raceGender[1][race]}}" alt height="22" width="22"></td>
                <td style="text-align: center;">{{race}}</td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="details" ng-if="showDetails" style="display: inline-block;">
          <table class="table table-bordered table-condensed">
            <tbody>
              <tr>
                <td colspan="14" style="text-align: center;">
                  Details
                </td>
              </tr>
              <tr>
                <td></td>
                <td></td>
                <td ng-repeat="(class, val) in raceClassCombos['Dwarf']">
                  <img ng-src="{{classesUrls[class]}}" alt height="22" width="22">
                </td>
              </tr>
              <tr ng-repeat="(race, classes) in raceClassCombos">
                <td>
                  <img ng-src="{{factionUrls[raceFactions[race]]}}" alt height="22" width="22">
                </td>
                <td>
                  <span>
                    <img ng-src="{{raceGender[0][race]}}" alt height="22" width="22">
                  </span>
                  <span>
                    <img ng-src="{{raceGender[1][race]}}" alt height="22" width="22">
                  </span>
                </td>
                <td ng-repeat="(class,val) in classes track by $index">
                  <div ng-if="val == 1" class="glyphicon glyphicon-ok" style="color:lightgreen;"></div>
                  <div ng-if="val == 0" class="glyphicon glyphicon-remove" style="color:red;"></div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <div id="characterSearchResults" ng-if="characters.length > 0 && showCharacterSearchResults == true">
        <table class="table">
          <thead>
            <tr>
              <th>#</th>
              <th>Name</th>
              <th>Level</th>
              <th>Class</th>
              <th>Race</th>
              <th>Faction</th>
              <th>Realm</th>
              <th>Battlegroup</th>
            </tr>
          </thead>
          <tbody>
            <tr ng-repeat="character in characters">
              <td>{{$index +1}}</td>
              <td ng-click="generateCharacterPane(character.name, character.realm)">
                <div class="pointer" ng-style="{color: ($index + 1) == selectedCharacterIndex ? 'red' : 'lightgreen'}" ng-click="selectCharacterIndex($index+1)">{{character.name}}</div>
              </td>
              <td>{{character.level}}</td>
              <td><img class="class" ng-src="{{character.classUrl}}" alt height="22" width="22"></td>
              <td><img class="race" ng-src="{{character.raceUrl}}" alt height="22" width="22"></td>
              <td><img class="faction" ng-src="{{character.factionUrl}}" alt height="22" width="22"></td>
              <td>{{character.realm}}</td>
              <td>{{character.battlegroup}}</td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="characterBlock" ng-if="showCharacterBlock">
        <div id="characterPane">
          <div class="left">
            <div class="margin-auto" style="display: inline-block;">
              <div class="margin-auto {{borderClass.head}}" style="padding: 6px;"><a class="pointer" ng-click="setItemDescriptionData('head')"><img class="border-gear-item {{gearClasses.head}}" ng-src="{{urls.head}}" alt="N/A" height="44" width="44"></a></div>
              <div class="margin-auto {{borderClass.neck}}" style="padding: 6px;"><a class="pointer" ng-click="setItemDescriptionData('neck')"><img class="border-gear-item {{gearClasses.neck}}" ng-src="{{urls.neck}}" alt="N/A" height="44" width="44"></a></div>
              <div class="margin-auto {{borderClass.shoulder}}" style="padding: 6px;"><a class="pointer" ng-click="setItemDescriptionData('shoulder')"><img class="border-gear-item {{gearClasses.shoulder}}" ng-src="{{urls.shoulder}}" alt="N/A" height="44" width="44"></a></div>
              <div class="margin-auto {{borderClass.back}}" style="padding: 6px;"><a class="pointer" ng-click="setItemDescriptionData('back')"><img class="border-gear-item {{gearClasses.back}}" ng-src="{{urls.back}}" alt="N/A" height="44" width="44"></a></div>
              <div class="margin-auto {{borderClass.chest}}" style="padding: 6px;"><a class="pointer" ng-click="setItemDescriptionData('chest')"><img class="border-gear-item {{gearClasses.chest}}" ng-src="{{urls.chest}}" alt="N/A" height="44" width="44"></a></div>
              <div class="margin-auto {{borderClass.wrist}}" style="padding: 6px;"><a class="pointer" ng-click="setItemDescriptionData('wrist')"><img class="border-gear-item {{gearClasses.wrist}}" ng-src="{{urls.wrist}}" alt="N/A" height="44" width="44"></a></div>
              <div class="margin-auto {{borderClass.hands}}" style="padding: 6px;"><a class="pointer" ng-click="setItemDescriptionData('hands')"><img class="border-gear-item {{gearClasses.hands}}" ng-src="{{urls.hands}}" alt="N/A" height="44" width="44"></a></div>
            </div>
          </div>
          <div class="center-bottom">
            <div style="margin-left:12%;">
              <div class="{{borderClass.mainHand}}" style="display: inline-block;"><a class="pointer" ng-click="setItemDescriptionData('mainHand')"><img class="border-gear-item {{gearClasses.mainHand}}" ng-src="{{urls.mainHand}}" alt="N/A" height="44" width="44"></a></div>
              <div class="{{borderClass.offHand}}" style="display: inline-block;"><a class="pointer" ng-click="setItemDescriptionData('offHand')"><img class="border-gear-item {{gearClasses.offHand}}" ng-src="{{urls.offHand}}" alt="N/A" height="44" width="44"></a></div>
            </div>
          </div>
          <div class="right">
            <div class="margin-auto">
              <div class="margin-auto {{borderClass.waist}}" style="padding: 6px;"><a class="pointer" ng-click="setItemDescriptionData('waist')"><img class="border-gear-item {{gearClasses.waist}}" ng-src="{{urls.waist}}" alt="N/A" height="44" width="44"></a></div>
              <div class="margin-auto {{borderClass.legs}}" style="padding: 6px;"><a class="pointer" ng-click="setItemDescriptionData('legs')"><img class="border-gear-item {{gearClasses.legs}}" ng-src="{{urls.legs}}" alt="N/A" height="44" width="44"></a></div>
              <div class="margin-auto {{borderClass.feet}}" style="padding: 6px;"><a class="pointer" ng-click="setItemDescriptionData('feet')"><img class="border-gear-item {{gearClasses.feet}}" ng-src="{{urls.feet}}" alt="N/A" height="44" width="44"></a></div>
              <div class="margin-auto {{borderClass.finger1}}" style="padding: 6px;"><a class="pointer" ng-click="setItemDescriptionData('finger1')"><img class="border-gear-item {{gearClasses.finger1}}" ng-src="{{urls.finger1}}" alt="N/A" height="44" width="44"></a></div>
              <div class="margin-auto {{borderClass.finger2}}" style="padding: 6px;"><a class="pointer" ng-click="setItemDescriptionData('finger2')"><img class="border-gear-item {{gearClasses.finger2}}" ng-src="{{urls.finger2}}" alt="N/A" height="44" width="44"></a></div>
              <div class="margin-auto {{borderClass.trinket1}}" style="padding: 6px;"><a class="pointer" ng-click="setItemDescriptionData('trinket1')"><img class="border-gear-item {{gearClasses.trinket1}}" ng-src="{{urls.trinket1}}" alt="N/A" height="44" width="44"></a></div>
              <div class="margin-auto {{borderClass.trinket2}}" style="padding: 6px;"><a class="pointer" ng-click="setItemDescriptionData('trinket2')"><img class="border-gear-item {{gearClasses.trinket2}}" ng-src="{{urls.trinket2}}" alt="N/A" height="44" width="44"></a></div>
            </div>
          </div>
        </div>
        <div class="rightCharacterInformationPane" style="display: inline-block;" ng-if="showRightCharacterInformationPane">
          <div class="focused-item-container {{gearInformation.rarityBorderClass}}">
            <div>
              <a class="pointer" ng-click="clickFocusedItem(gearInformation.id)">
                <img ng-src="{{urlFocused}}" alt="N/A" height="44" width="44">
              </a>
            </div>
            <div class="{{gearInformation.rarityClass}}">
              {{gearInformation.name}}
            </div>
            <div class="focused-item-description" style="color:yellow; font-style: italic;">
              {{gearInformation.description}}
            </div>
            <div class="focused-item-lvl">
              <span style="color:white">Item Level </span>
              <span>{{gearInformation.ilvl}}</span>
            </div>
            <div ng-repeat="(key, value) in gearInformation.stats">
              <span style="color:white">{{key}}:</span>
              <span ng-if="value>=0"> +</span>
              <span ng-if="value<0"> -</span>
              <span>{{value}}</span>
            </div>
          </div>
        </div>
      </div>
    <script src="./js/app.js"></script>
  </body>
</html>