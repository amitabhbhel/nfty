import React from 'react';


function Table() {
   // const [data, setData] = React.useState([]);

   return (
      <div className="overflow-x-auto">
         <table className="table table-sm">
            <thead>
               <tr>
                  <th>Name</th>
                  <th>Job</th>
                  <th>company</th>
                  <th>location</th>
                  <th>Last Login</th>
                  <th>Favorite Color</th>
               </tr>
            </thead>
            <tbody>
               <tr>
                  <td>Cy Ganderton</td>
                  <td>Quality Control Specialist</td>
                  <td>Littel, Schaden and Vandervort</td>
                  <td>Canada</td>
                  <td>12/16/2020</td>
                  <td>Blue</td>
               </tr>
               <tr>
                  <td>Hart Hagerty</td>
                  <td>Desktop Support Technician</td>
                  <td>Zemlak, Daniel and Leannon</td>
                  <td>United States</td>
                  <td>12/5/2020</td>
                  <td>Purple</td>
               </tr>
               <tr>
                  <td>Brice Swyre</td>
                  <td>Tax Accountant</td>
                  <td>Carroll Group</td>
                  <td>China</td>
                  <td>8/15/2020</td>
                  <td>Red</td>
               </tr>
               <tr>
                  <td>Marjy Ferencz</td>
                  <td>Office Assistant I</td>
                  <td>Rowe-Schoen</td>
                  <td>Russia</td>
                  <td>3/25/2021</td>
                  <td>Crimson</td>
               </tr>
               <tr>
                  <td>Yancy Tear</td>
                  <td>Community Outreach Specialist</td>
                  <td>Wyman-Ledner</td>
                  <td>Brazil</td>
                  <td>5/22/2020</td>
                  <td>Indigo</td>
               </tr>
            </tbody>
            <tfoot>
               <tr>
                  <th>Name</th>
                  <th>Job</th>
                  <th>company</th>
                  <th>location</th>
                  <th>Last Login</th>
                  <th>Favorite Color</th>
               </tr>
            </tfoot>
         </table>

         <div className="collapse bg-base-100 border border-base-300">
            <input id="collapse-1-toggle" type="checkbox" className="peer" />
            <label htmlFor="collapse-1-toggle" className="fixed inset-0 hidden peer-checked:block"></label>
            <div className="collapse-title font-semibold">How do I create an account?</div>
            <div className="collapse-content text-sm z-1">
               Click the "Sign Up" button in the top right corner and follow the registration process.
            </div>
         </div>

         <div class="collapse collapse-arrow bg-base-100 border border-base-300">
            <input type="radio" name="my-accordion-2" checked="checked" />
            <div class="collapse-title font-semibold">How do I create an account?</div>
            <div class="collapse-content text-sm">Click the "Sign Up" button in the top right corner and follow the registration process.</div>
         </div>
      </div>
   )
};

export default Table;