<table class="table">
    <tbody>
        <tr>
            <td>User Id</td>
            <td class="text-end"><?= $user->user_id ?></td>
        </tr>
        <tr>
            <td>User Name</td>
            <td class="text-end"><?= $user->full_name ?></td>
        </tr>
        <tr>
            <td>Total Investment</td>
            <td class="text-end"><?= f_amount($totalInvestment) ?></td>
        </tr>
        <tr>
            <td>Total Team Business</td>
            <td class="text-end"><?= f_amount($totalTeamBusiness) ?></td>
        </tr>
        <tr>
            <td>Power Leg / Other Legs - Business</td>
            <td class="text-end"><?= f_amount($powerLegBusiness) . ' / ' . f_amount($otherLegBusiness) ?></td>
        </tr>
    </tbody>
</table>