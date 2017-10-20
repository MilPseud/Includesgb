<?php function grades()

{ global $db, $_POST, $_SESSION, $_GET;
?>			
				
				
				<table cellspacing="5" cellpadding="0" style="text-align: center;">
					                                                                       
					<tbody>
						                                                                       
						<tr>
							                                                                       
							<th class="member_top">
								                                    Grades Staff                                    
							</th>
							                                                                       
						</tr>
						
						<tr class="memberbg_7">
							                                                                         
							<td>
								                                    <img src="pics/rank/Grade6.png" alt="MJS" style="width: 150px; height: 150px;" />                                    
							</td>
							                                                                         
							<td>
								                                    <h2><span style="color:#AA0000;font-weight: bold">Maîtres du Jeu Superviseurs</span></h2>Un Maître du Jeu Superviseur est un des Chefs du Staff de GaaranStröm ce qui lui donne un droit de veto sur les décisions du staff.
																	<br>
																	<br>Il est incontestable mais est néanmoins humain et peut faire des erreurs.
																	<br>Ils sont à la tête du Staff, ils ont le véto sur les décisions staff.
							                                                                         
						</tr>
						                                                           
						<tr class="memberbg_7">
							                                                                         
							<td>
								                                    <img src="pics/rank/Grade5.png" alt="MJ" style="width: 150px; height: 150px;" />                                    
							</td>
							                                                                         
							<td>
								                                    <h2><span style="color:#FFAA00;font-weight: bold">Maîtres du Jeu</span></h2>Un Maître du Jeu gère les encadrements du serveur et il peut donner des ordres aux Cadres.
																	<br>
																	<br>Il a accès à des actions qui sont en général incontestables par les joueurs. 
																	<br>
																	<br>Evidemment l'erreur est humaine et si un Maître du Jeu en commet les joueurs peuvent le corriger sans risquer une quelconque sanction.
																	
							</td>
							                                                                         
						</tr>
						       
						<tr class="memberbg_7">
							                                                                         
							<td>
								                                    <img src="pics/rank/Grade4.png" alt="Cadre" style="width: 150px; height: 150px;" />                                    
							</td>
							                                                                         
							<td>
								                                    <h2><span style="color:#00AAAA;font-weight: bold">Cadres</span></h2>Un Cadre est un Semi-MJ, il a pour rôle d'aider les Maîtres de Jeu dans leurs encadrements.
																	<br>
																	<br>Ils peuvent même effectuer des actions d'encadrement notées en bleu pouvant être contestées.
																	<br>
																	<br>Lors de la gradation, le futur Cadre doit avoir un MJ référent qui le prendra sous son aile et avec lequel il pourra effectuer des encadrements en binôme.                               
							</td>
							                                                                         
						</tr>
						                 
						<tr>
							     
							<th class="member_top">
								                                    Grades Spéciaux                                    
							</th>
							                                                                       
						</tr>
						
						
						<tr class="memberbg_6">
							                                                                         
							<td>
								                                    <img src="pics/rank/GradeD.png" alt="D" style="width: 150px; height: 150px;" />                                    
							</td>
							                                                                         
							<td>
								                                    <h2><span style="color:#AA00AA;font-weight: bold">Di</span><span style="color:#FFFF55;font-weight: bold">gni</span><span style="color:#5555FF;font-weight: bold">taire</span></h2>Un MJE, MJ, ou Cadre, qui n'exerce désormais plus ses pouvoirs, par choix ou par décision collective.
							</td>
							                                                                         
						</tr>
						
						<tr class="memberbg_6">
							                                                                         
							<td>
								                                    <img src="pics/rank/GradeDEP.png" alt="DEP" style="width: 150px; height: 150px;" />                                    
							</td>
							                                                                         
							<td>
								                                    <h2><span style="color:#0200A6;font-weight: bold">Personnages</span> <span style="color:#55FFFF;font-weight: bold">Non</span> <span style="color:#AAAAAA;font-weight: bold">Joueur</span></h2>Tous les personnages spéciaux et événementiels qui sont joués à titre exceptionnel par les Maîtres de Jeu, inutiles comme importants.                               
							</td>
							                                                                         
						</tr>
						
						<tr class="memberbg_6">
							                                                                         
							<td>
								                                    <img src="pics/rank/GradeBAN.png" alt="Bannis" style="width: 150px; height: 150px;" />                                    
							</td>
							                                                                         
							<td>
								                                    <h2><span style="color:#FF8A00;font-weight: bold">Bannis</span></h2>Une personne bannie a été exclue du serveur, définitivement ou non, car elle n'en a pas respecté les règles à un stade grave.                               
							</td>
							                                                                         
						</tr>
						
						<tr class="memberbg_6">
							                                                                         
							<td>
								                                    <img src="pics/rank/GradeDEL.png" alt="PNJ" style="width: 150px; height: 150px;" />                                    
							</td>
							                                                                         
							<td>
								                                    <h2><span style="color:#6A400F;font-weight: bold">Déserteurs</span></h2>Un Déserteur est une personne ayant supprimée elle même son compte.                               
							</td>
							                                                                         
						</tr>
						
						<tr class="memberbg_6">
							                                                                         
							<td>
								                                    <img src="pics/rank/Grade3.png" alt="VIP" style="width: 150px; height: 150px;" />                                    
							</td>
							                                                                         
							<td>
								                                    <h2><span style="color:#FF55FF;font-weight: bold">VIP</span></h2>Un VIP est une personne ayant apportée une aide très généreuse au serveur (en général des dons ou une publicité positive à haute échelle).
																	<br>Un VIP n'est pas forcément joueur de GaaranStröm.                                   
							</td>
							                                                                         
						</tr>
						
						<tr class="memberbg_6">
							                                                                         
							<td>
								                                    <img src="pics/rank/Grade2.png" alt="Alpha" style="width: 150px; height: 150px;" />                                    
							</td>
							                                                                         
							<td>
								                                    <h2><span style="color:#55FF55;font-weight: bold">Joueurs Alpha</span></h2>Un Joueur Alpha est un des premiers joueurs du serveurs, l'ayant découvert en avant-première ce qui lui offre une certaine avance.
																	<br>Ils méritent un certain respect pour leur ancienneté.                            
							</td>
							                                                                         
						</tr>
						       
						<tr class="memberbg_6">
							                                                                         
							<td>
								                                    <img src="pics/rank/GradeA.png" alt="Actif" style="width: 150px; height: 150px;" />                                    
							</td>
							                                                                         
							<td>
								                                    <h2><span style="color:#FF5555;font-weight: bold">Joueurs Actifs</span></h2>Un Joueur Actif est un joueur s'étant démarqué par une importante présence et participation en jeu.                              
							</td>
							                                                                         
						</tr>
						
						<tr>
							 
							<th class="member_top">
								                                    Grades Joueurs                                    
							</th>
							                                                                       
						</tr>
						                                                                       
						<tr class="memberbg_5">
							                                                                         
							<td>
								                                    <img src="pics/rank/Grade1.png" alt="Joueur" style="width: 150px; height: 150px;" />                                    
							</td>
							                                                                         
							<td>
								                                    <h2><span style="color:#00AA00;font-weight: bold">Joueurs</span></h2>Un Joueur est une personne au premier stade de connaissance du jeu. Il est évidemment voué à changer de grade en fonction de son implication et de ses connaissances en jeu, en plus de la qualité de son RP.                                 
							</td>
							                                                                         
						</tr>
						                                                           
						<tr class="memberbg_5">
							                                                                         
							<td>
								                                    <img src="pics/rank/Grade0.png" alt="Visiteur" style="width: 150px; height: 150px;" />                                    
							</td>
							                                                                         
							<td>
								                                    <h2><span style="color:#555550;font-weight: bold">Visiteurs</span></h2>Un Visiteur est une personne dont la candidature n'a pas encore été acceptée, ce grade est extrêmement limité en pouvoirs et empêche tout débordement sur le site.                           
							</td>
							                                                                         
						</tr>
						                                                       
					</tbody>
					                                                               
				</table>
<?php
}
?>